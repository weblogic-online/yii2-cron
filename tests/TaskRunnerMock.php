<?php
namespace weblogic\cron_tests;

use weblogic\cron\components\TaskRunner;

class TaskRunnerMock extends TaskRunner
{
    /**
     * @param string $level
     * @param string $message
     */
    protected static function log($level, $message) {
    }

}