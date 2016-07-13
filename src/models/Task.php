<?php

namespace vm\cron\models;

use vm\cron\components\TaskInterface;
use vm\cron\components\TaskRunInterface;
use yii\db\ActiveRecord;

/**
 * @author mult1mate
 * Date: 20.12.15
 * Time: 20:54
 * @property int    $id
 * @property string $time
 * @property string $command
 * @property string $status
 * @property string $comments 'comment' is a reserved word in some DBMS, we use 'comments' so that escaping is not necessary
 * @property string $ts
 * @property string $ts_updated
 */
class Task extends ActiveRecord implements TaskInterface
{
    /**
     * @return string
     */
    public static function tableName()
    {
        return '{{%tasks}}';
    }

    /**
     * @param int $taskId
     * @return null|static
     */
    public static function taskGet($taskId)
    {
        return self::findOne($taskId);
    }

    /**
     * List of all tasks
     * @return array|ActiveRecord[]|TaskInterface[] the query results. If the query results in nothing, an empty array will be returned.
     */
    public static function getList()
    {
        return self::find()->where(['not', ['status' => TaskInterface::TASK_STATUS_DELETED]])
            ->orderBy('status, id')->all();
    }

    /**
     * @return static[]
     */
    public static function getAll()
    {
        return self::find()->all();
    }

    /**
     * Date arithmetic only valid for MySQL
     * @param string $dateBegin
     * @param string $dateEnd
     * @return array
     */
    public static function getReport($dateBegin, $dateEnd)
    {
        $sql = "SELECT t.command, t.id,
        SUM(CASE WHEN tr.status = 'started' THEN 1 ELSE 0 END) AS started,
        SUM(CASE WHEN tr.status = 'completed' THEN 1 ELSE 0 END) AS completed,
        SUM(CASE WHEN tr.status = 'error' THEN 1 ELSE 0 END) AS error,
        round(AVG(tr.execution_time),2) AS time_avg,
        count(*) AS runs
        FROM task_runs AS tr
        LEFT JOIN tasks t ON t.id = tr.task_id
        WHERE tr.ts BETWEEN :date_begin AND :date_end + INTERVAL 1 DAY
        GROUP BY command
        ORDER BY tr.task_id";

        return \Yii::$app->db->createCommand($sql, [
            ':date_begin' => $dateBegin,
            ':date_end'   => $dateEnd,
        ])->queryAll();
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['time', 'command', 'status'], 'required'],
            [['time', 'status'], 'string', 'max' => 64],
            [['command'], 'string', 'max' => 256],
        ];
    }

    /**
     * @inheritdoc
     */
    public function taskDelete()
    {
        return $this->delete();
    }

    /**
     * @inheritdoc
     */
    public function taskSave()
    {
        return $this->save();
    }

    /**
     * @return Task
     */
    public static function createNew()
    {
        return new self();
    }

    /**
     * @return TaskRunInterface
     */
    public function createTaskRun()
    {
        return new TaskRun();
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
     * @return string
     */
    public function getTs()
    {
        return $this->ts;
    }

    /**
     * @param string $timestamp
     */
    public function setTs($timestamp)
    {
        $this->ts = $timestamp;
    }

    /**
     * @return string
     */
    public function getTsUpdated()
    {
        return $this->ts_updated;
    }

    /**
     * @param string $timestamp
     */
    public function setTsUpdated($timestamp)
    {
        $this->ts_updated = $timestamp;
    }
}
