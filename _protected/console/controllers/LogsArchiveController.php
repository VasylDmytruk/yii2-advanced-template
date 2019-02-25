<?php

namespace console\controllers;

use common\models\logs\archives\LogArchive;
use Yii;
use yii\console\Controller;
use yii\helpers\Console;

/**
 * Class LogsArchiveController
 */
class LogsArchiveController extends Controller
{
    /**
     * Deletes all records in table log_archive.
     */
    public function actionClear()
    {
        $deleted = LogArchive::deleteAll();

        $message = "Deleted $deleted logs archive";

        Yii::info($message, self::class);

        $this->stdout($message . PHP_EOL, Console::FG_GREEN);
    }
}
