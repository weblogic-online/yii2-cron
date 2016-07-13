<?php
namespace vm\cron_tests;

use vm\cron\components\TaskInterface;

/**
 * @author mult1mate
 * Date: 01.02.16
 * Time: 10:07
 */
class TaskMock implements TaskInterface
{
    private $id;
    private $time;
    private $command;
    private $status;
    private $comments;
    private $timestamp;
    private $ts_updated;

    public static function taskGet($taskId)
    {
        return new self();
    }

    public static function getAll()
    {
        return [];
    }

    public function taskDelete()
    {
        return true;
    }

    public function taskSave()
    {
        return true;
    }

    /**
     * @return TaskInterface
     */
    public static function createNew()
    {
        return new self();
    }

    /**
     * @return \vm\cron\components\TaskRunInterface
     */
    public function createTaskRun()
    {
        return new TaskRunMock();
    }

    public function getId()
    {
        return $this->id;
    }

    /**
     * @return mixed
     */
    public function getTime()
    {
        return $this->time;
    }

    /**
     * @param mixed $time
     */
    public function setTime($time)
    {
        $this->time = $time;
    }

    /**
     * @return mixed
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @param mixed $status
     */
    public function setStatus($status)
    {
        $this->status = $status;
    }

    /**
     * @return mixed
     */
    public function getCommand()
    {
        return $this->command;
    }

    /**
     * @param mixed $command
     */
    public function setCommand($command)
    {
        $this->command = $command;
    }

    /**
     * @return mixed
     */
    public function getComment()
    {
        return $this->comments;
    }

    /**
     * @param mixed $comment
     */
    public function setComment($comment)
    {
        $this->comments = $comment;
    }

    /**
     * @return mixed
     */
    public function getTs()
    {
        return $this->timestamp;
    }

    /**
     * @param mixed $timestamp
     */
    public function setTs($timestamp)
    {
        $this->timestamp = $timestamp;
    }

    /**
     * @return mixed
     */
    public function getTsUpdated()
    {
        return $this->ts_updated;
    }

    /**
     * @param mixed $timestamp
     */
    public function setTsUpdated($timestamp)
    {
        $this->ts_updated = $timestamp;
    }
}
