<?php

namespace XApi\Plugin;

use Phalcon\Db\Column;
use Phalcon\Db\ColumnInterface;
use Phalcon\Db\IndexInterface;
use Phalcon\Mvc\User\Plugin;
use Phalcon\Text;

class ModelGenerator extends Plugin
{
    //配置数据
    public $prefix = 'tb_';
    public $namespace = 'XApi\Models';
    public $modelsDir = APP_PATH . '/common/models';

    public function setConnectionService($connectionService)
    {
        $this->db = $this->di->get($connectionService);
    }

    /**
     * @param array $attributes
     * @return string
     */
    public function renderAttributes($attributes)
    {
        $code = '';
        foreach ($attributes as $key => $value) {
            $v = var_export($value, true);
            $code .= is_null($value) ? "\n    public \${$key};" : "\n    public \${$key} = {$v};";
        }
        return $code;
    }

    /**
     * @param array $methods
     * @return string
     */
    public function renderMethods($methods)
    {
        $code = '';
        foreach ($methods as $method) {
            $code .= <<<php

    public function {$method['name']}()
	{
{$method['code']}
	}
php;
        }
        return $code;
    }

    public function renderClass($namespace, $name, $extends = null, $attributesCode = null, $methodsCode = null)
    {
        $namespace = empty($namespace) ? '' : "namespace {$namespace};";
        $extends = empty($extends) ? '' : "extends {$extends}";
        $code = <<<php
<?php
{$namespace}

class {$name} {$extends}
{
$attributesCode
$methodsCode
}
php;
        return $code;
    }

    /**
     * @param $tableName
     */
    public function generateBaseModel($tableName)
    {
        $descriptor = $this->db->getDescriptor();
        $className = 'Base' . ucfirst(preg_replace_callback('/_[a-z]/', function ($match) {
                return strtoupper(substr($match[0], 1));
            }, substr($tableName, strlen($this->prefix))));
        $requiredColumns = [];
        $result = $this->db->query("show full fields from `{$tableName}` from `{$descriptor['dbname']}`")->fetchAll();
        $labels = array_column($result, 'Comment', 'Field');
        foreach ($labels as $key => &$value) {
            $value = $value ?: Text::camelize($key);
        }
        $validationCode = '';
        $attributes = [];
        $columns = $this->db->describeColumns($tableName, $descriptor['dbname']);
        foreach ($columns as $column) {
            $attributes[$column->getName()] = $column->hasDefault() && $column->getType() != Column::TYPE_DATETIME ? $column->getDefault() : null;
            //生成验证信息
            $label = $labels[$column->getName()];
            if ($column->isPrimary() && $column->isAutoIncrement()) {
                continue;
            }
            switch ($column->getType()) {
                case Column::TYPE_DECIMAL:
                case Column::TYPE_FLOAT:
                case Column::TYPE_DOUBLE:
                    $validationCode .= $this->generateNumericalityValidation($column, $label);
                    break;
                case Column::TYPE_INTEGER:
                case Column::TYPE_BIGINTEGER:
                    $validationCode .= $this->generateIntegerValidation($column, $label);
                    break;
                case Column::TYPE_DATETIME:
                    $validationCode .= $this->genrateDatetimeValidation($column, $label);
                    break;
                case Column::TYPE_CHAR:
                case Column::TYPE_VARCHAR:
                    $validationCode .= $this->generateStringLengthValidation($column, $label);
                    break;
                default:
                    break;
            }
            if (!$column->hasDefault() && $column->isNotNull()) {
                if (in_array($column->getType(), [Column::TYPE_TEXT]))
                    continue;
                $validationCode .= $this->generatePresenceValidation($column, $label);
                $requiredColumns[] = $column->getName();
            }
        }
        $indexes = $this->db->describeIndexes($tableName, $descriptor['dbname']);
        foreach ($indexes as $index) {
            $labelData = [];
            foreach ($index->getColumns() as $column) {
                $labelData[] = $labels[$column];
            }
            if ($index->getType() === 'UNIQUE') {
                $validationCode .= $this->generateUniquenessValidation($index, implode('-', $labelData));
            }
        }
        $validationCode = <<<php
        \$validator = new \Phalcon\Validation();
{$validationCode}
        return \$this->validate(\$validator);
php;
        $initializeCode = <<<php
        method_exists(get_parent_class(), 'initialize') ? parent::initialize() : false;
        \$this->setSource('{$tableName}');
        \$this->useDynamicUpdate(true);
php;

        $methods = [
            [
                'name' => 'initialize',
                'code' => $initializeCode,
            ],
            [
                'name' => 'getLabels',
                'code' => "\t\t" . 'return ' . str_replace("\n", "\n\t\t", var_export($labels, true) . ';'),
            ],
            [
                'name' => 'validation',
                'code' => $validationCode,
            ],
            [
                'name' => 'requiredColumns',
                'code' => "\t\t" . 'return ' . str_replace("\n", "\n\t\t", var_export($requiredColumns, true) . ';'),
            ]
        ];

        $finalCode = $this->renderClass($this->namespace, $className, 'Model', $this->renderAttributes($attributes), $this->renderMethods($methods));
        $filename = Text::concat("/", $this->modelsDir, "{$className}.php");
        file_put_contents($filename, $finalCode);
    }

    public function generateChildModel($tableName)
    {
        $className = ucfirst(preg_replace_callback('/_[a-z]/', function ($match) {
            return strtoupper(substr($match[0], 1));
        }, substr($tableName, strlen($this->prefix))));
        $baseClassName = "Base$className";
        $code = $this->renderClass($this->namespace, $className, $baseClassName);
        $filename = $this->modelsDir . '/' . $className . '.php';
        if (!file_exists($filename)) {
            file_put_contents($filename, $code);
        }
    }

    public function generateModel($tableName)
    {
        $this->generateBaseModel($tableName);
        $this->generateChildModel($tableName);
    }

    public function generatePresenceValidation(ColumnInterface $column, $label)
    {
        $validationString = <<<eot

        \$validator->add('{$column->getName()}', new \Phalcon\Validation\Validator\PresenceOf(array(
            'message' => '{$label}必须填写',
        )));
eot;
        return $validationString;
    }

    public function generateIntegerValidation(ColumnInterface $column, $label)
    {
        $validationString = <<<eot

        \$validator->add('{$column->getName()}', new \Phalcon\Validation\Validator\Digit(array(
            'message' => '{$label}必须为整数',
            'allowEmpty' => true,
        )));
eot;
        return $validationString;
    }

    public function generateNumericalityValidation(ColumnInterface $column, $label)
    {
        $validationString = <<<eot
        
        \$validator->add('{$column->getName()}', new \Phalcon\Validation\Validator\Numericality(array(
            'message' => '{$label}必须为数字',
            'allowEmpty' => true,
        )));
eot;
        return $validationString;
    }

    public function generateStringLengthValidation(ColumnInterface $column, $label)
    {
        $max = $column->getSize();
        if ($max == 0) {
            return '';
        }
        $validationString = <<<eot
        
        \$validator->add('{$column->getName()}', new \Phalcon\Validation\Validator\StringLength(array(
            'max' => '$max',
            'min' => '0',
            'messageMaximum' => '{$label}字符数最大为$max',
            'messageMinimum' => '{$label}字符数最小为0'
        )));
eot;
        return $validationString;
    }

    public function genrateDatetimeValidation(ColumnInterface $column, $label)
    {
        $validationString = <<<eot

        \$validator->add('{$column->getName()}', new \Phalcon\Validation\Validator\Date(array(
            'format' => 'Y-m-d H:i:s',
            'allowEmpty' => true,
            'message' => '{$label}不符合日期格式',
        )));
eot;
        return $validationString;
    }

    public function generateUniquenessValidation(IndexInterface $index, $label)
    {
        $columnName = str_replace("\n", '', var_export($index->getColumns(), true));
        $validationString = <<<eot
        
        \$validator->add($columnName, new \Phalcon\Validation\Validator\Uniqueness(array(
            'model' => \$this,
            'allowEmpty' => true,
            'message' => '{$label}已存在',
        )));
eot;
        return $validationString;
    }

    public function createModels()
    {
        $tables = $this->db->listTables();
        foreach ($tables as $table) {
            if ($this->prefix === '' || strstr($table, $this->prefix) !== false) {
                $this->generateModel($table);
            }
        }
        return true;
    }
}
