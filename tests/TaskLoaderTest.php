<?php
namespace vm\cron_tests;

use vm\cron\TaskLoader;

/**
 * @author mult1mate
 * Date: 07.02.16
 * Time: 13:49
 */
class TaskLoaderTest extends \PHPUnit_Framework_TestCase
{
    public function testSetClassFolder()
    {
        $set = TaskLoader::setClassFolder(__DIR__);
        $this->assertTrue(is_array($set));
    }

    public function testGetAllMethods()
    {
        $result = TaskLoader::getAllMethods(
            [__DIR__ . '/..', __DIR__, __DIR__ . '/correct_mocks'],
            [null, 'mult1mate\\crontab_tests\\']
        );
        $this->assertTrue(is_array($result));
    }

    public function testGetAllMethodsExceptions()
    {
        $this->setExpectedException('vm\cron\TaskManagerException');
        TaskLoader::getAllMethods('/mocks/');
    }

    public function testGetControllerMethodsExceptions()
    {
        $this->setExpectedException('vm\cron\TaskManagerException');
        TaskLoader::getControllerMethods('/mocks/');
    }

    public function testLoadControllerExceptionsFile()
    {
        $this->setExpectedException('vm\cron\TaskManagerException');
        TaskLoader::setClassFolder(__DIR__ . '/wrong_mocks');
        TaskLoader::loadController('FileWithoutClass');
    }

    public function testLoadControllerExceptions()
    {
        $this->setExpectedException('vm\cron\TaskManagerException');
        TaskLoader::setClassFolder(__DIR__);
        TaskLoader::loadController('MockClass');
    }
}
