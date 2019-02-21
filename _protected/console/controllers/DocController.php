<?php

namespace console\controllers;

use Yii;
use yii\console\Controller;

/**
 * Class DocController
 */
class DocController extends Controller
{
    /**
     * Moves doc assets.
     *
     * @param string $from Path from move.
     * @param string $to Path to move.
     */
    public function actionMoveAssets($from, $to)
    {
        try {
            Yii::$app->doc->moveAssets($from, $to);
        } catch (\Throwable $e) {
            Yii::error(
                $this->action->uniqueId . PHP_EOL . $e->getMessage() . PHP_EOL . $e->getTraceAsString(),
                self::class
            );
        }
    }
}
