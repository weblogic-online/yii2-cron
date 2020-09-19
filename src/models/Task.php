<?php

    namespace weblogic\cron\models;

    use DateTime;
    use DateTimeZone;
    use InvalidArgumentException;
    use LogicException;
    use Throwable;
    use weblogic\cron\components\TaskInterface;
    use weblogic\cron\components\TaskRunInterface;
    use weblogic\cron\components\validation\CommandValidator;
    use weblogic\cron\Module;
    use Yii;
    use yii\behaviors\TimestampBehavior;
    use yii\db\ActiveRecord;
    use yii\db\Exception;

    /**
     * @property int    $id
     * @property string $time
     * @property string $command
     * @property string $status
     * @property string $comments 'comment' is a reserved word in some DBMS, we use 'comments' so that escaping is not necessary
     * @property string $ts
     * @property string $ts_updated
     * @property int    $locked
     */
    class Task extends ActiveRecord implements TaskInterface
    {

        /**
         * @return array
         */
        public function attributeLabels()
        {
            return [
                'id' => Yii::t('cron', 'ID'),
                'time' => Yii::t('cron', 'Time expression'),
                'command' => Yii::t('cron', 'Command'),
                'status' => Yii::t('cron', 'Status'),
                'comments' => Yii::t('cron', 'Comment'),
                'comment' => Yii::t('cron', 'Comment'),
                'ts' => Yii::t('cron', 'Created'),
                'ts_updated' => Yii::t('cron', 'Updated'),
                'locked' => Yii::t('cron', 'Locked'),
            ];
        }

        /**
         * @return string
         */
        public static function tableName()
        {
            return '{{%tasks}}';
        }

        /**
         * @inheritdoc
         */
        public function rules()
        {
            return [
                [['time', 'command', 'status'], 'required'],
                [['time', 'status'], 'string', 'max' => 64],
                [['command', 'comments'], 'string', 'max' => 256],
//         /   [['comments', 'time', 'command', 'status'], 'match', 'pattern' => '/[\<]+/', 'not' => true],
                ['command', 'match', 'pattern' => '/([@\w\\\\]+)::(\w+)\((.*)\)/'],
                ['command', CommandValidator::class, 'skipOnEmpty' => false],
                ['ts_updated', 'filter', 'filter' => function () {
                    $date = new DateTime('now', new DateTimeZone("UTC"));
                    return $date->format('Y-m-d H:i:s');
                }],
                ['ts_updated', 'date', 'format' => 'php:Y-m-d H:i:s'],
                ['ts', 'default', 'value' => function () {
                    $date = new DateTime('now', new DateTimeZone("UTC"));
                    return $date->format('Y-m-d H:i:s');
                }],
                ['ts', 'date', 'format' => 'php:Y-m-d H:i:s']
            ];
        }

        public function behaviors()
        {
            return [
                TimestampBehavior::class,
            ];
        }

        /**
         * @param int $taskId
         *
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
         *
         * @param string $dateBegin
         * @param string $dateEnd
         * @param string $sqlDialect
         *
         * @return array
         * @throws Exception
         */
        public static function getReport($dateBegin, $dateEnd, $sqlDialect = Module::DIALECT_MYSQL)
        {
            $sql = "SELECT t.command, t.id,
        SUM(CASE WHEN tr.status = 'started' THEN 1 ELSE 0 END) AS started,
        SUM(CASE WHEN tr.status = 'completed' THEN 1 ELSE 0 END) AS completed,
        SUM(CASE WHEN tr.status = 'error' THEN 1 ELSE 0 END) AS error,
        round(AVG(tr.execution_time),2) AS time_avg,
        round(MIN(tr.execution_time),2) AS time_min,
        round(MAX(tr.execution_time),2) AS time_max,
        count(*) AS runs
        FROM task_runs tr
        LEFT JOIN tasks t ON t.id = tr.task_id
        WHERE " . self::getDateConstraint($sqlDialect) . "
        GROUP BY t.command, t.id
        ORDER BY t.id";

            return Yii::$app->db->createCommand($sql, [
                ':date_begin' => $dateBegin,
                ':date_end' => $dateEnd,
            ])->queryAll();
        }

        /**
         * get the date constraint for the given SQL dialect
         *
         * @param string $sqlDialect
         *
         * @return string
         */
        protected static function getDateConstraint($sqlDialect = Module::DIALECT_MYSQL)
        {
            switch ($sqlDialect) {
                case Module::DIALECT_MYSQL:
                    $constraint = 'tr.ts BETWEEN :date_begin AND :date_end + INTERVAL 1 DAY';
                    break;
                case Module::DIALECT_OCI8:
                    $constraint = "tr.ts BETWEEN TO_DATE(:date_begin, 'YYYY-MM-DD HH24:MI:SS') 
                    AND TO_DATE(:date_end, 'YYYY-MM-DD HH24:MI:SS') + 1";
                    break;
                default:
                    throw new InvalidArgumentException('SQL Dialect "' . $sqlDialect . '" is not implemented in ' . __METHOD__);
            }
            return $constraint;
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
            $taskRun = new TaskRun();
            $taskRun->setTaskId($this->id);
            return $taskRun;
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

        /**
         * @return bool
         */
        public function isLocked()
        {
            return (bool)$this->locked;
        }

        /**
         * sets the locked flag to 0 in the database
         */
        public function releaseLock()
        {
            $this->locked = 0;
            $this->update();
        }

        /**
         * @param int|bool $locked
         */
        public function setLocked($locked)
        {
            $this->locked = intval($locked);
        }

        /**
         * @return bool
         * @throws \Exception
         * @throws Throwable
         */
        public function acquireLock()
        {
            if (!$this->id) {
                throw new LogicException('Task ID must be set to acquire a lock');
            }
            $db = Yii::$app->getDb();
            $transaction = $db->beginTransaction();
            try {
                // get the current lock status and lock the row in the database
                $query = $db->createCommand(
                    'SELECT locked FROM ' . self::tableName() . ' WHERE id = :id' . ' FOR UPDATE',
                    [':id' => $this->id]
                );
                $locked = $query->queryScalar();

                if ($locked == 1) {
                    // task is already locked
                    $transaction->commit();
                    $this->locked = 1;
                    Yii::info('Tried to acquire a lock for the task with ID ' . $this->id . ', but it is already/still locked');
                    return false;
                } elseif ($locked === 0 OR $locked === '0') {
                    // task was found and is not locked
                    $this->locked = 1;
                    // make sure that this attribute is written, even when Yii does not think it is a "dirty attribute".
                    // this can happen, when the task was initially locked, but another process released the lock in the meantime.
                    $this->markAttributeDirty('locked');
                    $result = $this->update();
                    $transaction->commit();
                    if ($result > 0) {
                        // locking was successful
                        return true;
                    } else {
                        // affected rows not > 0
                        Yii::error('Tried to lock the task with ID ' . $this->id . ', but the database reported zero affected rows');
                        return false;
                    }
                } else {
                    // unexpected value for "locked"
                    $transaction->commit();
                    Yii::error('Tried to look up the lock status of the task with ID ' . $this->id
                        . ', but a value other than 0/1 or no value was returned: "' . $locked . '"');
                    return false;
                }
            } catch (\Exception $e) {
                $transaction->rollBack();
                throw $e;
            }
        }

    }
