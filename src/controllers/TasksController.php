<?php

    namespace weblogic\cron\controllers;

    use weblogic\cron\components\TaskManagerException;
    use weblogic\cron\CronModule;
    use weblogic\cron\models\Task;
    use weblogic\cron\models\TaskRun;
    use weblogic\cron\assets\TasksAsset;
    use weblogic\cron\components\TaskInterface;
    use weblogic\cron\components\TaskLoader;
    use weblogic\cron\components\TaskManager;
    use weblogic\cron\components\TaskRunner;
    use Yii;
    use yii\data\ActiveDataProvider;
    use yii\helpers\Url;
    use yii\web\Controller;

    class TasksController extends Controller
    {
        /** @var string */
        protected static $tasksControllersFolder;

        /** @var string */
        protected static $tasksNamespace;

        /**
         * @param string     $id
         * @param CronModule $module
         * @param array      $config
         */
        public function __construct($id, $module, $config = [])
        {
            parent::__construct($id, $module, $config);
            self::$tasksControllersFolder = $module->tasksControllersFolder;
            self::$tasksNamespace = $module->tasksNamespace;
            TasksAsset::register($this->view);
        }

        public function actionIndex()
        {
            $query = Task::find()->where(['not', ['status' => TaskInterface::TASK_STATUS_DELETED]]);

            $dataProvider = new ActiveDataProvider([
                'query' => $query,
                'sort' => ['defaultOrder' => ['id' => 'asc']]
            ]);

            return $this->render('index', [
                'dataProvider' => $dataProvider,
                'tasks' => Task::getList(),
                'methods' => TaskLoader::getAllMethods(self::$tasksControllersFolder, self::$tasksNamespace),
            ]);
        }

        public function actionExport()
        {
            return $this->render('export');
        }

        public function actionParseCrontab()
        {
            $crontab = Yii::$app->request->post('crontab');
            if ($crontab) {
                $result = TaskManager::parseCrontab($crontab, new Task());
                echo json_encode($result);
            }
        }

        public function actionExportTasks()
        {
            $folder = Yii::$app->request->post('folder');
            if ($folder) {
                $tasks = Task::getList();
                $result = [];
                foreach ($tasks as $t) {
                    $line = TaskManager::getTaskCrontabLine(
                        $t,
                        $folder,
                        Yii::$app->request->post('php'),
                        Yii::$app->request->post('file')
                    );
                    $result[] = nl2br($line);
                }
                echo json_encode($result);
            }
        }

        /**
         * show the last 30 runs of the given task
         * @return string
         */
        public function actionShowLog()
        {
            $taskId = Yii::$app->request->get('id');
            $runs = TaskRun::getLastRuns($taskId, 30);

            return $this->render('runs_list', ['runs' => $runs]);
        }

        /**
         * execute one or more selected tasks
         */
        public function actionRunTask()
        {
            $tasks = Yii::$app->request->post('id');
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
            } elseif (($customTask = Yii::$app->request->post('custom_task'))) {
                $result = TaskRunner::parseAndRunCommand($customTask);
                echo $result ? 'success' : 'failed';
            } else {
                echo Yii::t('cron', 'empty task id');
            }
        }

        /**
         * display the next run dates for the given cron expression
         */
        public function actionGetDates()
        {
            $time = Yii::$app->request->post('time');
            if (empty($time)) {
                echo 'n/a';
                return;
            }
            $dates = TaskRunner::getRunDates($time);
            if (empty($dates)) {
                echo Yii::t('cron', 'Invalid expression');
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
            $taskRunId = Yii::$app->request->post('task_run_id');
            if ($taskRunId) {
                $run = TaskRun::findOne($taskRunId);
                /**
                 * @var TaskRun $run
                 */
                echo htmlentities($run->getOutput());
            } else {
                echo Yii::t('cron', 'empty task run id');
            }
        }

        /**
         * edit one single task
         * @return string
         * @throws TaskManagerException
         */
        public function actionUpdate()
        {
            $taskId = Yii::$app->request->get('id');
            if ($taskId) {
                $task = Task::findOne($taskId);
            } else {
                $task = new Task();
            }
            $post = Yii::$app->request->post();
            if ($task->load($post) && $task->validate() && $task->save()) {
                Yii::$app->session->setFlash('success', Yii::t('cron', 'The task has been saved'));
                return Yii::$app->response->redirect(Url::toRoute(['index']));
            }

            return $this->render('update', [
                'task' => $task,
                'methods' => TaskLoader::getAllMethods(self::$tasksControllersFolder, self::$tasksNamespace),
            ]);
        }

        /**
         * set the status of one ore more tasks
         * called by the mass update function in the list view
         */
        public function actionTasksUpdate()
        {
            $taskIds = Yii::$app->request->post('id');
            $mode = Yii::$app->request->post('mode');
            $modes = [
                'Enable' => TaskInterface::TASK_STATUS_ACTIVE,
                'Disable' => TaskInterface::TASK_STATUS_INACTIVE,
                'Delete' => TaskInterface::TASK_STATUS_DELETED,
            ];
            if ($taskIds AND isset($modes[$mode])) {
                $tasks = Task::findAll($taskIds);
                $numUpdated = 0;
                foreach ($tasks as $t) {
                    /** @var Task $t */
                    $t->setStatus($modes[$mode]);
                    $numUpdated += $t->save();
                }
                Yii::$app->session->setFlash(
                    $numUpdated ? 'success' : 'warning',
                    Yii::t('cron', '{n,plural,=0{no tasks have} =1{one task has} other{# tasks have}} been updated', ['n' => $numUpdated]),
                    false
                );
                return "";
            }
            return Yii::$app->response->redirect(Url::toRoute(['index']));
        }

        public function actionTasksReport()
        {
            $dateBegin = Yii::$app->request->get('date_begin', date('Y-m-d', strtotime('-6 day')));
            $dateEnd = Yii::$app->request->get('date_end', date('Y-m-d'));

            return $this->render('report', [
                'report' => Task::getReport($dateBegin, $dateEnd, $this->module->sqlDialect),
                'dateBegin' => $dateBegin,
                'dateEnd' => $dateEnd,
            ]);
        }
    }
