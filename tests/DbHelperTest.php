<?php
namespace rossmann\cron_tests;

use rossmann\cron\helpers\DbHelper;

/**
 * @author mult1mate
 * @since 07.02.2016
 */
class DbHelperTest extends \PHPUnit_Framework_TestCase
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
