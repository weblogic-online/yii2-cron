<?php
namespace vm\cron\controllers;

use vm\cron\models\Task;
use vm\cron\models\TaskRun;
use vm\cron\assets\TasksAsset;
use vm\cron\components\TaskInterface;
use vm\cron\components\TaskLoader;
use vm\cron\components\TaskManager;
use vm\cron\components\TaskRunner;
use yii\web\Controller;

/**
 * @author mult1mate
 * Date: 20.12.15
 * Time: 20:56
 */
class TasksController extends Controller
{
    private static $tasks_controllers_folder;
    private static $tasks_namespace;

    public function __construct($id, $module, $config = [])
    {
        parent::__construct($id, $module, $config);
        self::$tasks_controllers_folder = __DIR__ . '/../models/';
        self::$tasks_namespace          = 'vm\\cron\\models\\';
        TasksAsset::register($this->view);
    }

    public function actionIndex()
    {
        return $this->render('tasks_list', [
            'tasks'   => Task::getList(),
            'methods' => TaskLoader::getAllMethods(self::$tasks_controllers_folder, self::$tasks_namespace),
        ]);
    }

    public function actionExport()
    {
        return $this->render('export');
    }

    public function actionParseCrontab()
    {
        $crontab = \Yii::$app->request->post('crontab');
        if ($crontab) {
            $result = TaskManager::parseCrontab($crontab, new Task());
            echo json_encode($result);
        }
    }

    public function actionExportTasks()
    {
        $folder = \Yii::$app->request->post('folder');
        if ($folder) {
            $tasks  = Task::getList();
            $result = [];
            foreach ($tasks as $t) {
                $line = TaskManager::getTaskCrontabLine(
                    $t,
                    $folder,
                    \Yii::$app->request->post('php'),
                    \Yii::$app->request->post('file')
                );
                $result[] = nl2br($line);
            }
            echo json_encode($result);
        }
    }

    public function actionTaskLog()
    {
        $task_id = \Yii::$app->request->get('task_id');
        $runs    = TaskRun::getLast($task_id);

        return $this->render('runs_list', ['runs' => $runs]);
    }

    public function actionRunTask()
    {
        $tasks = \Yii::$app->request->post('id');
        if (!empty($tasks)) {
            $tasks = !is_array($tasks) ? [$tasks] : $tasks;
            foreach ($tasks as $t) {
                $task = Task::findOne($t);
                /**
                 * @var Task $task
                 */
                $output = TaskRunner::runTask($task);
                echo($output . '<hr>');
            }
        } elseif (($custom_task = \Yii::$app->request->post('custom_task'))) {
            $result = TaskRunner::parseAndRunCommand($custom_task);
            echo $result ? 'success' : 'failed';
        } else {
            echo 'empty task id';
        }
    }

    public function actionGetDates()
    {
        $time  = \Yii::$app->request->post('time');
        $dates = TaskRunner::getRunDates($time);
        if (empty($dates)) {
            echo 'Invalid expression';

            return;
        }
        echo '<ul>';
        foreach ($dates as $d) {
            /**
             * @var \DateTime $d
             */
            echo '<li>' . $d->format('Y-m-d H:i:s') . '</li>';
        }
        echo '</ul>';
    }

    public function actionGetOutput()
    {
        $task_run_id = \Yii::$app->request->post('task_run_id');
        if ($task_run_id) {
            $run = TaskRun::findOne($task_run_id);
            /**
             * @var TaskRun $run
             */
            echo htmlentities($run->getOutput());
        } else {
            echo 'empty task run id';
        }
    }

    public function actionTaskEdit()
    {
        $task_id = \Yii::$app->request->get('task_id');
        if ($task_id) {
            $task = Task::findOne($task_id);
        } else {
            $task = new Task();
        }
        /**
         * @var Task $task
         */
        $post = \Yii::$app->request->post();
        if ($task->load($post) && $task->validate()) {
            $task = TaskManager::editTask(
                $task,
                $post['Task']['time'],
                $post['Task']['command'],
                $post['Task']['status'],
                $post['Task']['comment']
            );
            \Yii::$app->response->redirect(['/tasks/task-edit', 'task_id', $task->task_id]);
        }

        return $this->render('task_edit', [
            'task'    => $task,
            'methods' => TaskLoader::getAllMethods(self::$tasks_controllers_folder, self::$tasks_namespace),
        ]);
    }

    public function actionTasksUpdate()
    {
        $task_id = \Yii::$app->request->post('task_id');
        if ($task_id) {
            $tasks = Task::findAll($task_id);
            foreach ($tasks as $t) {
                /**
                 * @var Task $t
                 */
                $action_status = [
                    'Enable'  => TaskInterface::TASK_STATUS_ACTIVE,
                    'Disable' => TaskInterface::TASK_STATUS_INACTIVE,
                    'Delete'  => TaskInterface::TASK_STATUS_DELETED,
                ];
                $t->setStatus($action_status[\Yii::$app->request->post('action')]);
                $t->save();
            }
        }
    }

    public function actionTasksReport()
    {
        $date_begin = \Yii::$app->request->get('date_begin', date('Y-m-d', strtotime('-6 day')));
        $date_end   = \Yii::$app->request->get('date_end', date('Y-m-d'));

        return $this->render('report', [
            'report'     => Task::getReport($date_begin, $date_end),
            'date_begin' => $date_begin,
            'date_end'   => $date_end,
        ]);
    }
}
