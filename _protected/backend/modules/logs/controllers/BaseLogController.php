<?php

namespace backend\modules\logs\controllers;

use backend\controllers\BackendController;
use yii\base\InvalidConfigException;
use yii\db\ActiveRecord;

/**
 * Class BaseLogController
 */
abstract class BaseLogController extends BackendController
{
    /**
     * @var string|ActiveRecord
     */
    protected $logModel;
    /**
     * @var string|ActiveRecord
     */
    protected $logSearchModel;
    /**
     * @var string
     */
    protected $indexTitle;


    /**
     * @inheritdoc
     * @throws InvalidConfigException
     */
    public function init()
    {
        parent::init();

        if (empty($this->logModel) || empty($this->logSearchModel) || empty($this->indexTitle)) {
            throw new InvalidConfigException('Params "logModel", "logSearchModel", "indexTitle" are required');
        }
    }
}
