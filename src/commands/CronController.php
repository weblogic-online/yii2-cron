<?php
/**
 * @author mult1mate
 * Date: 06.02.16
 * Time: 16:52
 */
namespace vm\cron\commands;

use vm\cron\models\Task;
use vm\cron\components\TaskRunner;
use yii\console\Controller;

class CronController extends Controller
{
    public function actionCheckTasks()
    {
        TaskRunner::checkAndRunTasks(Task::getAll());
    }
}
