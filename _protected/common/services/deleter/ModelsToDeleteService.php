<?php

namespace common\services\deleter;

use Yii;
use yii\base\BaseObject;
use yii\base\InvalidConfigException;
use yii\db\ActiveRecord;
use yii\helpers\ArrayHelper;

/**
 * Class ModelsToDeleteService Processes data of params of models to delete.
 * Works with config param [[ModelsToDeleteService::MODELS_TO_DELETE]].
 * Example:
 *
 * ```
 * ModelsToDeleteService::MODELS_TO_DELETE => [
 *      \common\models\Log::class => [
 *           ModelsToDeleteService::MODEL_NAME_KEY => \common\models\Log::class,
 *           ModelsToDeleteService::LOG_TIME_ATTR_KEY => 'log_time',
 *           ModelsToDeleteService::LOG_TIME_MULTIPLIER_KEY => 1,
 *      ],
 *      \common\models\LogSipProxy::class => [
 *           ModelsToDeleteService::MODEL_NAME_KEY => \common\models\LogSipProxy::class,
 *           ModelsToDeleteService::LOG_TIME_ATTR_KEY => 'log_time',
 *           ModelsToDeleteService::LOG_TIME_MULTIPLIER_KEY => 1000, // because in LogSipProxy we store time in milliseconds
 *      ],
 * ],
 * ```
 */
class ModelsToDeleteService extends BaseObject
{
    public const MODELS_TO_DELETE = 'modelsToDelete';
    /**
     * Some models stores data in seconds, some in milliseconds,
     * those which stores in milliseconds we use multiplier 1000 and 1 for seconds.
     */
    public const LOG_TIME_MULTIPLIER_KEY = 'logTimeMultiplier';
    public const LOG_TIME_ATTR_KEY = 'logTimeAttribute';
    public const MODEL_NAME_KEY = 'modelName';
    public const PREVENT_DELETING_LAST_DAYS = 'preventDeletingLastDays';
    public const ALLOWED_TO_DELETE = 'allowedToDelete';

    /**
     * @var null|array
     */
    private $_modelsToDeleteParams = null;


    /**
     * @inheritdoc
     * @throws InvalidConfigException
     */
    public function init()
    {
        parent::init();

        if (!isset(Yii::$app->params[static::MODELS_TO_DELETE])) {
            throw new InvalidConfigException('Add to config params ' . static::MODELS_TO_DELETE);
        }

        $this->_modelsToDeleteParams = Yii::$app->params[static::MODELS_TO_DELETE];
    }

    /**
     * @param string $modelClass
     * @param string $key
     *
     * @return mixed
     *
     * @throws InvalidConfigException
     */
    private function getValueAfterCheck(string $modelClass, string $key)
    {
        if (!isset($this->_modelsToDeleteParams[$modelClass][$key])) {
            throw new InvalidConfigException("'$modelClass' must contain " . static::LOG_TIME_ATTR_KEY);
        }

        return $this->_modelsToDeleteParams[$modelClass][$key];
    }

    /**
     * Gets log time attribute for model.
     *
     * @param string $modelClass Model to find attribute.
     *
     * @return string
     *
     * @throws InvalidConfigException
     */
    public function getTimeAttribute(string $modelClass): string
    {
        $logTimeAttribute = $this->getValueAfterCheck($modelClass, static::LOG_TIME_ATTR_KEY);

        return $logTimeAttribute;
    }

    /**
     * Checks whether model is allowed to delete.
     *
     * @param string $modelClass Model class name to find attribute.
     *
     * @return bool Returns `true` if `$modelClass` is allowed to delete.
     *
     * @throws InvalidConfigException
     */
    public function isAllowedToDelete(string $modelClass): bool
    {
        $allowedToDelete = $this->getValueAfterCheck($modelClass, static::ALLOWED_TO_DELETE);

        return $allowedToDelete;
    }

    /**
     * Gets until value multiplied by concrete model multiplier.
     * Some models stores data in seconds, some in milliseconds,
     * those which stores in milliseconds we use multiplier 1000 and 1 for seconds.
     *
     * @param string $modelClass
     *
     * @return float|int Returns time multiplier.
     *
     * @throws InvalidConfigException
     */
    public function getTimeMultiplier(string $modelClass)
    {
        $timeMultiplier = $this->getValueAfterCheck($modelClass, static::LOG_TIME_MULTIPLIER_KEY);

        return $timeMultiplier;
    }

    /**
     * Gets map of models to delete (model name becomes key and value, usefully for dropdowns).
     *
     * @return array
     */
    public function getModelsMapName(): array
    {
        $modelsMapName = [];

        foreach ($this->_modelsToDeleteParams as $modelsToDeleteParam) {
            if ($modelsToDeleteParam[static::ALLOWED_TO_DELETE]) {
                $modelName = $modelsToDeleteParam[static::MODEL_NAME_KEY];
                $modelsMapName[$modelName] = $modelName;
            }
        }

        return $modelsMapName;
    }

    /**
     * Gets value of prevent deleting last days config param.
     *
     * @return int
     */
    public function preventDeletingLastDays(): int
    {
        $preventDeletingLastDays = Yii::$app->params[static::PREVENT_DELETING_LAST_DAYS];

        return $preventDeletingLastDays;
    }

    /**
     * Gets prevent deleting last days value in unix timestamp multiplied by model multiplier.
     *
     * @param string $modelClass Model class name to find attribute.
     *
     * @return int Returns prevent deleting last days value in unix timestamp multiplied by model multiplier.
     *
     * @throws InvalidConfigException
     */
    public function getPreventDeletingLastDaysTimeStampMultiplied(string $modelClass): int
    {
        $multiplier = $this->getTimeMultiplier($modelClass);
        $preventDeletingLastDaysTimeStamp = $this->getPreventDeletingLastDaysTimeStamp();
        $preventDeletingLastDaysTimeStampMultiplied = $preventDeletingLastDaysTimeStamp * $multiplier;

        return $preventDeletingLastDaysTimeStampMultiplied;
    }

    /**
     * Gets prevent deleting last days value in unix timestamp.
     *
     * @return int
     */
    public function getPreventDeletingLastDaysTimeStamp(): int
    {
        $preventDeletingLastDays = Yii::$app->params[static::PREVENT_DELETING_LAST_DAYS];
        $modify = "- $preventDeletingLastDays days";

        $preventDeletingLastDaysDate = new \DateTime();
        $preventDeletingLastDaysDate->modify($modify);
        $preventDeletingLastDaysTimeStamp = $preventDeletingLastDaysDate->getTimestamp();

        return $preventDeletingLastDaysTimeStamp;
    }

    /**
     * Gets string for DatePicker for plugin option 'endDate'.
     *
     * @return string
     */
    public function getDatePickerDeleteEndDate(): string
    {
        $preventDeletingLastDays = Yii::$app->params[static::PREVENT_DELETING_LAST_DAYS];
        $deleteEndDate = '-' . $preventDeletingLastDays . 'd';

        return $deleteEndDate;
    }
}
