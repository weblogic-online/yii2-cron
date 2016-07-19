<?php
namespace rossmann\cron_tests;

use rossmann\cron\components\TaskInterface;
use rossmann\cron\components\TaskLoader;

/**
 * @author mult1mate
 * @since 07.02.2016
 */
class TaskRunnerTest extends \PHPUnit_Framework_TestCase
{
    public function testCheckAndRunTasks()
    {
        $taskInactive  = TaskMock::createNew();
        $task          = TaskMock::createNew();
        $task->setStatus(TaskInterface::TASK_STATUS_ACTIVE);
        $task->setTime('* * * * *');
        TaskRunnerMock::checkAndRunTasks([$task, $taskInactive]);
    }

    public function testGetRunDates()
    {
        $result = TaskRunnerMock::getRunDates('* * * * *');
        $this->assertTrue(is_array($result));
        $this->assertEquals(10, count($result));
    }

    public function testGetRunDatesException()
    {
        $result = TaskRunnerMock::getRunDates('wrong expression');
        $this->assertTrue(is_array($result));
        $this->assertEquals(0, count($result));
    }

    public function testParseAndRunCommand()
    {
        $result = TaskRunnerMock::parseAndRunCommand('rossmann\cron_tests\ActionMock::returnResult()');
        $this->assertTrue($result);

        $result = TaskRunnerMock::parseAndRunCommand('rossmann\cron_tests\ActionMock::wrongMethod()');
        $this->assertFalse($result);

        TaskLoader::setClassFolder(__DIR__ . '/runner_mocks');
        $result = TaskRunnerMock::parseAndRunCommand('RunnerMock::anyMethod()');
        $this->assertFalse($result);
    }
}
