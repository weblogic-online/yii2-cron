<?php

    namespace weblogic\cron\commands;

    use weblogic\cron\models\Task;
    use weblogic\cron\components\TaskRunner;
    use yii\console\Controller;

    class CronController extends Controller
    {
        public function actionCheckTasks()
        {
            TaskRunner::checkAndRunTasks(Task::getAll());
        }
    }
