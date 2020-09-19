<?php

    namespace weblogic\cron\models;

    use weblogic\cron\components\TaskRunInterface;
    use yii\db\ActiveRecord;
    use yii\db\Query;

    /**
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
         * @param int $taskId
         * @param int $count
         *
         * @return TaskRun[]
         */
        public static function getLastRuns($taskId = null, $count = 100)
        {
            $db = (new Query())
                ->select('task_runs.*, tasks.command')
                ->from(self::tableName())
                ->join('LEFT JOIN', 'tasks', 'tasks.id = task_runs.task_id')
                ->orderBy('task_runs.id desc')
                ->limit($count);
            if ($taskId) {
                $db->where('task_runs.task_id=:task_id', [':task_id' => $taskId]);
            }

            return $db->all();
        }

        /**
         * @param int $taskId
         *
         * @return array|TaskRun
         */
        public static function getLast($taskId)
        {
            return self::find()
                ->where('task_id = :task_id', [':task_id' => $taskId])
                ->orderBy('id DESC')->one();
        }

        /**
         * @inheritdoc
         */
        public function saveTaskRun() : bool
        {
            return $this->save();
        }

        /**
         * @return int
         */
        public function getId() : int
        {
            return $this->id;
        }

        /**
         * @return int
         */
        public function getTaskId() : int
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
        public function getStatus() : string
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
        public function getExecutionTime() : int
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
        public function getTs() : string
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
        public function getOutput() : string
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
