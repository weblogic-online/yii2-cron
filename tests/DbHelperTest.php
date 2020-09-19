<?php

    namespace weblogic\cron_tests;

    use PHPUnit_Framework_TestCase;
    use weblogic\cron\helpers\DbHelper;

    class DbHelperTest extends PHPUnit_Framework_TestCase
    {
        public function testGetReportSql()
        {
            $sql = DbHelper::getReportSql();
            $this->assertTrue(is_string($sql));
        }

        public function testTableTasksSql()
        {
            $sql = DbHelper::tableTasksSql();
            $this->assertTrue(is_string($sql));
        }

        public function testTableTaskRunsSql()
        {
            $sql = DbHelper::tableTaskRunsSql();
            $this->assertTrue(is_string($sql));
        }
    }
