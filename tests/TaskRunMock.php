<?php
namespace vm\cron_tests;

use vm\cron\components\TaskRunInterface;

/**
 * @author mult1mate
 * Date: 01.02.16
 * Time: 10:12
 */
class TaskRunMock implements TaskRunInterface
{
    private $id;
    private $task_id;
    private $status;
    private $output;
    private $execution_time;
    private $timestamp;

    public function saveTaskRun()
    {
        return true;
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return int
     */
    public function getTaskId()
    {
        return $this->task_id;
    }

    /**
     * @param int $taskId
     */
    public function setTaskId($taskId)
    {
        $this->task_id = $taskId;
    }

    /**
     * @return string
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @param string $status
     */
    public function setStatus($status)
    {
        $this->status = $status;
    }

    /**
     * @return int
     */
    public function getExecutionTime()
    {
        return $this->execution_time;
    }

    /**
     * @param int $executionTime
     */
    public function setExecutionTime($executionTime)
    {
        $this->execution_time = $executionTime;
    }

    /**
     * @return string
     */
    public function getTs()
    {
        return $this->timestamp;
    }

    /**
     * @param string $timestamp
     */
    public function setTs($timestamp)
    {
        $this->timestamp = $timestamp;
    }

    public function getOutput()
    {
        return $this->output;
    }

    public function setOutput($output)
    {
        $this->output = $output;
    }
}