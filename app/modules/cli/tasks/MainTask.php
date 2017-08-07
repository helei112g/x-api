<?php
namespace XApi\Modules\Cli\Tasks;

use XApi\Utils\Console;

class MainTask extends \Phalcon\Cli\Task
{
    /**
     * 显示系统所有命令
     */
    public function mainAction()
    {
        $commands = [
            'version    '  => '查看当前系统版本',
            'model      '  => '生成系统model',
            'test       '  => '测试系统api接口是否正常',
        ];

        Console::stdout("当前可执行命令：" . PHP_EOL, Console::FG_YELLOW);

        foreach ($commands as $key => $help) {
            Console::stdout("  $key\t", Console::FG_GREEN);
            Console::stdout($help . PHP_EOL);
        }

        Console::stdout(PHP_EOL . "命令后跟 help 查看更多信息", Console::FG_BLUE);
    }

}
