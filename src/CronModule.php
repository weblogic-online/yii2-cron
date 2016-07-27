<?php
/**
 * @copyright Copyright (c) 2013-2016 Voodoo Mobile Consulting Group LLC
 * @link      https://voodoo.rocks
 * @license   http://opensource.org/licenses/MIT The MIT License (MIT)
 */
namespace rossmann\cron;

use yii\base\Exception;

/**
 * Class CronModule
 */
class CronModule extends \yii\base\Module
{
    const DIALECT_MYSQL = 'MySQL';
    const DIALECT_OCI8 = 'Oracle';

    /**
     * @var string
     */
    public $controllerNamespace = 'rossmann\cron\controllers';

    /**
     * Which SQL dialect should be used to generate date expressions
     * Oracle uses the TO_DATE function
     * @var string
     */
    public $sqlDialect = 'MySQL';

    /**
     * in which path to look for controller classes containing task actions
     * @var string|array
     */
    public $tasksControllersFolder = [];

    /**
     * the namespace of the controller classes found in $tasksControllersFolder
     * @var string|array
     */
    public $tasksNamespace = [];

    /**
     * @throws Exception
     */
    public function init()
    {
        parent::init();
    }
}
