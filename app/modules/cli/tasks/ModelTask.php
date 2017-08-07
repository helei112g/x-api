<?php
/**
 * Created by PhpStorm.
 * User: helei
 * Date: 2017/8/7
 * Time: 下午5:08
 */

namespace XApi\Modules\Cli\Tasks;


use Phalcon\Cli\Task;
use XApi\Utils\Console;

/**
 * 生成model命令
 * Class ModelTask
 * @package XApi\Modules\Cli\Tasks
 */
class ModelTask extends Task
{
    private $models = [
        'member',
        'member_account'
    ];

    // 这里一定要三个 \\\
    /**
     * @var string $command
     */
    private static $command = 'phalcon model %name% --namespace=XApi\\\Models --name=%name%s --force';

    /**
     * 获取帮助信息
     */
    public function mainAction()
    {
        Console::stdout("Desc:" . PHP_EOL, Console::FG_GREEN);
        Console::stdout("  根据数据库表生成model" . PHP_EOL . PHP_EOL);

        Console::stdout("Usage:" . PHP_EOL, Console::FG_GREEN);
        Console::stdout("  php run model [options]" . PHP_EOL . PHP_EOL, Console::FG_YELLOW);

        Console::stdout("Options:" . PHP_EOL, Console::FG_GREEN);
        Console::stdout("  all   \t", Console::FG_YELLOW);
        Console::stdout("重新生产所有model" . PHP_EOL);
        Console::stdout("  name  \t", Console::FG_YELLOW);
        Console::stdout("根据name生成指定model" . PHP_EOL);
    }

    /**
     * 生成所有的model信息
     */
    public function allAction()
    {
        foreach ($this->models as $model) {
            $runCommand = str_replace('%name%', $model, self::$command);

            system($runCommand, $returnVar);

            Console::stdout( "执行：{$runCommand}，返回结果：{$returnVar}" . PHP_EOL);
        }
    }

    /**
     * 根据名称生成
     * @param $params
     */
    public function nameAction($params)
    {
        $name = $params ? $params[0] : '';
        if (in_array($name, $this->models)) {
            $runCommand = str_replace('%name%', $name, self::$command);

            system($runCommand, $returnVar);

            Console::stdout("执行：{$runCommand}，返回结果：{$returnVar}");
        } else {
            Console::stderr("model: {$name} 在系统中不存在." . PHP_EOL, Console::FG_RED);
            Console::stdout('当前可执行的命令：' . PHP_EOL, Console::FG_GREEN);
            Console::stdout(implode(PHP_EOL, $this->models), Console::FG_YELLOW);
        }
    }
}