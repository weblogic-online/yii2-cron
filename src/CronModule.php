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
    /**
     * @var string
     */
    public $controllerNamespace = 'rossmann\cron\controllers';

    /**
     * @throws Exception
     */
    public function init()
    {
        parent::init();
    }
}
