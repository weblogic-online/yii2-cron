<?php
namespace rossmann\cron_tests;

use rossmann\cron\components\TaskInterface;

/**
 * @author mult1mate
 * @since 01.02.2016
 */
class TaskMock implements TaskInterface
{
    protected $id;
    protected $time;
    protected $command;
    protected $status;
    protected $comments;
    protected $timestamp;
    protected $ts_updated;
    protected $locked;

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
     * @return \rossmann\cron\components\TaskRunInterface
     */
    public function createTaskRun()
    {
        return new TaskRunMock();
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getTime()
    {
        return $this->time;
    }

    /**
     * @param string $time
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
     * @return string
     */
    public function getCommand()
    {
        return $this->command;
    }

    /**
     * @param string $command
     */
    public function setCommand($command)
    {
        $this->command = $command;
    }

    /**
     * @return string
     */
    public function getComment()
    {
        return $this->comments;
    }

    /**
     * @param string $comment
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

    /**
     * @return bool
     */
    public function isLocked() {
        return (bool) $this->locked;
    }

    /**
     * sets the locked flag to 0 in the database
     */
    public function releaseLock() {
        $this->locked = 0;
    }

    /**
     * @param int|bool $locked
     */
    public function setLocked($locked) {
        $this->locked = 1;
    }

    /**
     * @return bool
     * @throws \Exception
     */
    public function acquireLock() {
        if ($this->locked) {
            return false;
        } else {
            $this->locked = 1;
            return true;
        }
    }

}
