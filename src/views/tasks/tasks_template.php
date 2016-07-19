<?php
/**
 * @author mult1mate
 * @since 31.12.2015
 * @var string $content
 */
use yii\helpers\Html;

$menu = [
    'index'        => 'Tasks list',
    'task-edit'    => 'Add new/edit task',
    'task-log'     => 'Logs',
    'export'       => 'Import/Export',
    'tasks-report' => 'Report',
];
?>
<script src="manager_actions.js"></script>
<div class="col-lg-10">
    <h2>Cron tasks manager</h2>

    <ul class="nav nav-tabs">
        <?php foreach ($menu as $m => $text):
            $class = (isset($_GET['m']) && ($_GET['m'] == $m)) ? 'active' : '';
            ?>
            <li class="<?= $class ?>"><?= Html::a($text, $m) ?></li>
        <?php endforeach; ?>
    </ul>
    <br>
    <?= isset($content) ? $content : '' ?>
</div>
