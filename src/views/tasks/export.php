<?php
/**
 * @author mult1mate
 * @since 31.12.2015
 */
echo $this->render('tasks_template');
$this->title = 'Task Manager - Import/Export';
?>
<div class="col-lg-6">
    <h2>Import</h2>
    <form method="post" id="parse_crontab_form">
        <div class="form-group">
            Example:
            <pre>* * * * * cd /some/path; /usr/bin/php script.php \name\space\ClassName actionName 2>&1 > /dev/null</pre>
            <label for="crontab">Paste crontab content</label><br>
            <textarea class="form-control" name="crontab" id="crontab"></textarea>
        </div>
        <div class="form-group">
            <input type="submit" value="Parse" class="btn btn-primary">
        </div>
    </form>
    <div id="parse_result">
    </div>
</div>

<div class="col-lg-6">
    <h2>Export</h2>
    <form class="form-inline" id="export_form">
        <div class="form-group">
            <label class="control-label" for="php">Path to PHP</label>
            <input type="text" class="form-control" name="php" id="php" value="/usr/bin/php" style="width: 100px;">
        </div>
        <div class="form-group">
            <label class="control-label" for="folder">Path to folder</label>
            <input type="text" class="form-control" name="folder" id="folder" value="">
        </div>
        <div class="form-group">
            <label class="control-label" for="file">php file</label>
            <input type="text" class="form-control" name="file" id="file" value="index.php" style="width: 100px;">
        </div>
        <div class="form-group">
            <input type="submit" value="Export" class="btn btn-primary">
        </div>
    </form>
    <br>
    <pre id="export_result">

    </pre>
</div>
