<?php
namespace rossmann\cron_tests;

use rossmann\cron\components\TaskLoader;

/**
 * @author mult1mate
 * @since 07.02.2016
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
            [null, 'rossmann\\cron_tests\\']
        );
        $this->assertTrue(is_array($result));
    }

    public function testGetAllMethodsExceptions()
    {
        $this->setExpectedException('rossmann\cron\components\TaskManagerException');
        TaskLoader::getAllMethods('/mocks/');
    }

    public function testGetControllerMethodsExceptions()
    {
        $this->setExpectedException('rossmann\cron\components\TaskManagerException');
        TaskLoader::getControllerMethods('/mocks/');
    }

    public function testLoadControllerExceptionsFile()
    {
        $this->setExpectedException('rossmann\cron\components\TaskManagerException');
        TaskLoader::setClassFolder(__DIR__ . '/wrong_mocks');
        TaskLoader::loadController('FileWithoutClass');
    }

    public function testLoadControllerExceptions()
    {
        $this->setExpectedException('rossmann\cron\components\TaskManagerException');
        TaskLoader::setClassFolder(__DIR__);
        TaskLoader::loadController('MockClass');
    }
}
