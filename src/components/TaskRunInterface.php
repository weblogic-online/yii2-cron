<?php

    namespace weblogic\cron\components;

    /**
     * Interface TaskRunInterface
     * Common interface to handle task runs
     */
    interface TaskRunInterface
    {
        const RUN_STATUS_STARTED = 'started';
        const RUN_STATUS_COMPLETED = 'completed';
        const RUN_STATUS_ERROR = 'error';

        /**
         * Saves the task run
         * @return mixed
         */
        public function saveTaskRun();

        /**
         * @return int
         */
        public function getId();

        /**
         * @return int
         */
        public function getTaskId();

        /**
         * @param int $taskId
         */
        public function setTaskId($taskId);

        /**
         * @return string
         */
        public function getStatus();

        /**
         * @param string $status
         */
        public function setStatus($status);

        /**
         * @return string
         */
        public function getExecutionTime();

        /**
         * @param string $executionTime
         */
        public function setExecutionTime($executionTime);

        /**
         * @return string
         */
        public function getTs();

        /**
         * @param string $timestamp
         */
        public function setTs($timestamp);

        /**
         * @return string
         */
        public function getOutput();

        /**
         * @param string $output
         */
        public function setOutput($output);
    }
