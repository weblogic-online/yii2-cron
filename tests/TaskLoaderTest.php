<?php

    namespace weblogic\cron_tests;

    use PHPUnit_Framework_TestCase;
    use weblogic\cron\components\TaskLoader;

    class TaskLoaderTest extends PHPUnit_Framework_TestCase
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
                [null, 'weblogic\\cron_tests\\']
            );
            $this->assertTrue(is_array($result));
        }

        public function testGetAllMethodsExceptions()
        {
            $this->setExpectedException('weblogic\cron\components\TaskManagerException');
            TaskLoader::getAllMethods('/mocks/');
        }

        public function testGetControllerMethodsExceptions()
        {
            $this->setExpectedException('weblogic\cron\components\TaskManagerException');
            TaskLoader::getControllerMethods('/mocks/');
        }

        public function testLoadControllerExceptionsFile()
        {
            $this->setExpectedException('weblogic\cron\components\TaskManagerException');
            TaskLoader::setClassFolder(__DIR__ . '/wrong_mocks');
            TaskLoader::loadController('FileWithoutClass');
        }

        public function testLoadControllerExceptions()
        {
            $this->setExpectedException('weblogic\cron\components\TaskManagerException');
            TaskLoader::setClassFolder(__DIR__);
            TaskLoader::loadController('MockClass');
        }
    }
