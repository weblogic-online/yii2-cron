<?php

    namespace weblogic\cron\components\validation;

    use weblogic\cron\components\TaskManager;
    use Yii;
    use yii\base\Model;
    use yii\validators\Validator;

    class CommandValidator extends Validator
    {
        /**
         * @param Model $model
         * @param string             $attribute
         */
        public function validateAttribute($model, $attribute)
        {
            if ($command = TaskManager::validateCommand($model->$attribute)) {
                $model->$attribute = $command;
            } else {
                $this->addError($model, $attribute, Yii::t('cron', 'invalid_command'));
            }
        }
    }
