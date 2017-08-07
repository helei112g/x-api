<?php
namespace XApi\Modules\Cli\Tasks;

use XApi\Utils\Console;

class VersionTask extends \Phalcon\Cli\Task
{
    public function mainAction()
    {
        $config = $this->getDI()->get('config');

        Console::stdout($config['version'], Console::FG_GREEN);
    }
}
