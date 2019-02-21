<?php

namespace console\controllers;

use Yii;
use yii\console\Controller;

/**
 * Class CronController
 */
class CronController extends Controller
{
    /**
     * Archives all logs (Log) older than `Yii::$app->params[ModelsToDeleteService::PREVENT_DELETING_LAST_DAYS]`
     */
    public function actionArchiveAllLogs()
    {
        try {
            Yii::$app->logArchivator->archiveAll();
        } catch (\Throwable $e) {
            Yii::error($e->getMessage(), self::class);
        }
    }
}
