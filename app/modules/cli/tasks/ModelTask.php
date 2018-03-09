<?php
/**
 * Created by PhpStorm.
 * User: helei
 * Date: 2017/8/7
 * Time: 下午5:08
 */

namespace XApi\Modules\Cli\Tasks;


use Phalcon\Cli\Task;
use XApi\Plugin\ModelGenerator;
use XApi\Utils\Console;

/**
 * 生成model命令
 * Class ModelTask
 * @package XApi\Modules\Cli\Tasks
 */
class ModelTask extends Task
{
    /**
     * 获取帮助信息
     */
    public function mainAction()
    {
        Console::stdout("Tip:" . PHP_EOL, Console::FG_GREEN);
        Console::stdout("根据数据库表生成model" . PHP_EOL . PHP_EOL);

        $modelGenerator = new ModelGenerator();
        $modelGenerator->createModels();
    }
}
