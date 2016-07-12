<?php
namespace vm\cron\models;

use vm\cron\TaskRunInterface;
use yii\db\ActiveRecord;
use yii\db\Query;

/**
 * @author mult1mate
 * Date: 20.12.15
 * Time: 21:12
 * @property int    $id
 * @property int    $task_id
 * @property string $status
 * @property string $output
 * @property int    $execution_time
 * @property string $ts
 */
class TaskRun extends ActiveRecord implements TaskRunInterface
{
    /**
     * @return string
     */
    public static function tableName()
    {
        return '{{%task_runs}}';
    }

    /**
     * @param int $task_id
     * @param int $count
     * @return array
     */
    public static function getLast($task_id = null, $count = 100)
    {
        $db = (new Query())
            ->select('task_runs.*, tasks.command')
            ->from(self::tableName())
            ->join('LEFT JOIN', 'tasks', 'tasks.id = task_runs.task_id')
            ->orderBy('task_runs.id desc')
            ->limit($count);
        if ($task_id) {
            $db->where('task_runs.task_id=:task_id', [':id' => $task_id]);
        }

        return $db->all();
    }

    /**
     * @inheritdoc
     */
    public function saveTaskRun()
    {
        return $this->save();
    }

    /**
     * @return int
     */
    public function getTaskRunId()
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
     * @param int $task_id
     */
    public function setTaskId($task_id)
    {
        $this->task_id = $task_id;
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
     * @param int $execution_time
     */
    public function setExecutionTime($execution_time)
    {
        $this->execution_time = $execution_time;
    }

    /**
     * @return string
     */
    public function getTs()
    {
        return $this->ts;
    }

    /**
     * @param string $ts
     */
    public function setTs($ts)
    {
        $this->ts = $ts;
    }

    /**
     * @return string
     */
    public function getOutput()
    {
        return $this->output;
    }

    /**
     * @param string $output
     */
    public function setOutput($output)
    {
        $this->output = $output;
    }
}
