<?php
/**
 * @author mult1mate
 * Date: 21.12.15
 * Time: 0:38
 * @var array $tasks
 * @var array $methods
 */
use yii\helpers\Html;
use vm\cron\components\TaskInterface;

echo $this->render('tasks_template');
$this->title = 'Task Manager - Task list';
?>
<table class="table table-bordered">
    <tr>
        <th>
            <input type="checkbox" id="select_all" title="select all">
        </th>
        <th>ID</th>
        <th>Time</th>
        <th>Command</th>
        <th>Status</th>
        <th>Comment</th>
        <th>Created</th>
        <th>Updated</th>
        <th></th>
        <th></th>
        <th></th>
    </tr>
    <?php
    foreach ($tasks as $t):
        /**
         * @var \vm\cron\models\Task $t
         */
        $statusClass = (TaskInterface::TASK_STATUS_ACTIVE == $t->status) ? '' : 'text-danger';
        ?>
        <tr>
            <td>
                <input type="checkbox" value="<?= $t->id ?>" class="task_checkbox" title="select task">
            </td>
            <td><?= $t->id ?></td>
            <td><?= $t->time ?></td>
            <td><?= $t->command ?></td>
            <td class="<?= $statusClass ?>"><?= $t->status ?></td>
            <td><?= $t->comment ?></td>
            <td><?= $t->ts ?></td>
            <td><?= $t->ts_updated ?></td>
            <td>
                <?= Html::a('Edit', ['/cron/tasks/task-edit', 'id' => $t->id]); ?>
            </td>
            <td>
                <?= Html::a('Log', '/cron/tasks/task-log'); ?>
            </td>
            <td>
                <?= Html::a('Run', '', ['id' => $t->id, 'class' => 'run_task']); ?>
            </td>
        </tr>
    <?php endforeach; ?>
</table>
<form class="form-inline">
    <div class="form-group">
        <label for="action">With selected</label>
        <select class="form-control" id="action">
            <option>Enable</option>
            <option>Disable</option>
            <option>Delete</option>
            <option>Run</option>
        </select>
    </div>
    <div class="form-group">
        <input type="submit" value="Apply" class="btn btn-primary" id="execute_action">
    </div>
</form>
<form class="form-inline">
    <h3>Run custom task</h3>
    <div class="form-group">
        <label for="method">Methods</label>
        <select class="form-control" id="method">
            <option></option>
            <?php foreach ($methods as $class => $classMethods): ?>
                <optgroup label="<?= $class ?>">
                    <?php foreach ($classMethods as $method): ?>
                        <option value="<?= $class . '::' . $method . '()' ?>"><?= $method ?></option>
                    <?php endforeach; ?>
                </optgroup>
            <?php endforeach; ?>
        </select>
    </div>
    <div class="form-group">
        <label for="command">Command</label>
        <input type="text" class="form-control" id="command" name="command" placeholder="Controller::method"
               style="width: 300px;">
    </div>
    <input type="submit" value="Run" class="btn btn-primary" id="run_custom_task">
</form>
<div id="output_section" style="display: none;">
    <h3>Task output</h3>
    <pre id="task_output_container"></pre>
</div>
