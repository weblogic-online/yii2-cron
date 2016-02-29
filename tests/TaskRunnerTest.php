<?php
namespace vm\cron_tests;

use vm\cron\TaskInterface;
use vm\cron\TaskLoader;
use vm\cron\TaskRunner;

/**
 * @author mult1mate
 * Date: 07.02.16
 * Time: 14:15
 */
class TaskRunnerTest extends \PHPUnit_Framework_TestCase
{
    public function testCheckAndRunTasks()
    {
        $task_inactive = TaskMock::createNew();
        $task          = TaskMock::createNew();
        $task->setStatus(TaskInterface::TASK_STATUS_ACTIVE);
        $task->setTime('* * * * *');
        TaskRunner::checkAndRunTasks([$task, $task_inactive]);
    }

    public function testGetRunDates()
    {
        $result = TaskRunner::getRunDates('* * * * *');
        $this->assertTrue(is_array($result));
        $this->assertEquals(10, count($result));
    }

    public function testGetRunDatesException()
    {
        $result = TaskRunner::getRunDates('wrong expression');
        $this->assertTrue(is_array($result));
        $this->assertEquals(0, count($result));
    }

    public function testParseAndRunCommand()
    {
        $result = TaskRunner::parseAndRunCommand('vm\cron_tests\ActionMock::returnResult()');
        $this->assertTrue($result);

        $result = TaskRunner::parseAndRunCommand('vm\cron_tests\ActionMock::wrongMethod()');
        $this->assertFalse($result);

        TaskLoader::setClassFolder(__DIR__ . '/runner_mocks');
        $result = TaskRunner::parseAndRunCommand('RunnerMock::anyMethod()');
        $this->assertFalse($result);
    }
}
