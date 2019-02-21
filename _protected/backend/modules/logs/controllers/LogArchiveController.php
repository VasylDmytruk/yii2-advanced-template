<?php

namespace backend\modules\logs\controllers;

use common\models\logs\archives\LogArchive;
use common\models\logs\archives\LogArchiveSearch;

/**
 * Class LogArchiveController
 */
class LogArchiveController extends LogController
{
    protected $logModel = LogArchive::class;
    protected $logSearchModel = LogArchiveSearch::class;
    protected $indexTitle = 'Server logs archive';


    /**
     * @inheritdoc
     * @throws \yii\base\InvalidConfigException
     */
    public function init()
    {
        parent::init();

        $this->viewPath = '@backend/modules/logs/views/log';
    }
}
