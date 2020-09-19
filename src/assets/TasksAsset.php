<?php

    namespace weblogic\cron\assets;

    use yii\web\AssetBundle;

    class TasksAsset extends AssetBundle
    {
        public $sourcePath = '@vendor/weblogic-online/yii2-cron/src/assets';

        public $js = [
            'manager_actions.js',
        ];
    }
