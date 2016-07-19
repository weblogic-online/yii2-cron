<?php
namespace rossmann\cron_tests;

use rossmann\cron\components\TaskRunner;

class TaskRunnerMock extends TaskRunner
{
    /**
     * @param string $level
     * @param string $message
     */
    protected static function log($level, $message) {
    }

}