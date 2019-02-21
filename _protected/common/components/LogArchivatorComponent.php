<?php

namespace common\components;

use common\services\deleter\ModelsToDeleteService;
use Yii;
use yii\base\Component;
use yii\db\ActiveRecord;

/**
 * Class LogArchivatorComponent Archivates all logs (Log, LogSip, LogSipProxy)
 * older than `Yii::$app->params[ModelsToDeleteService::PREVENT_DELETING_LAST_DAYS]`
 */
class LogArchivatorComponent extends Component
{
    /**
     * @var array Map of log models to log archive models.
     */
    public $modelsLogMap = [
        \common\models\logs\Log::class => \common\models\logs\archives\LogArchive::class,
    ];

    /**
     * @var ModelsToDeleteService
     */
    protected $modelsToDeleteService;


    /**
     * LogArchivatorComponent constructor.
     *
     * @param ModelsToDeleteService $modelsToDeleteService
     * @param array $config
     */
    public function __construct(ModelsToDeleteService $modelsToDeleteService, array $config = [])
    {
        $this->modelsToDeleteService = $modelsToDeleteService;

        parent::__construct($config);
    }

    /**
     * Archivates all logs (Log, LogSip, LogSipProxy)
     * older than `Yii::$app->params[ModelsToDeleteService::PREVENT_DELETING_LAST_DAYS]`
     *
     * @throws \yii\base\InvalidConfigException
     * @throws \yii\db\Exception
     */
    public function archiveAll(): void
    {
        $start = time();

        foreach ($this->modelsLogMap as $logModel => $logArchiveModel) {
            $this->archiveLog($logModel, $logArchiveModel);
        }

        $end = time();

        $done = $end - $start;
        Yii::info("Done in $done seconds", self::class);
    }

    /**
     *
     *
     * @param string|ActiveRecord $logModel
     * @param string|ActiveRecord $logArchiveModel
     *
     * @throws \yii\base\InvalidConfigException
     * @throws \yii\db\Exception
     */
    private function archiveLog(string $logModel, string $logArchiveModel): void
    {
        $logTable = $logModel::tableName();
        $logArchiveTable = $logArchiveModel::tableName();

        $timeAttribute = $this->modelsToDeleteService->getTimeAttribute($logModel);
        $filterTimeValue = $this->modelsToDeleteService->getPreventDeletingLastDaysTimeStampMultiplied($logModel);

        $where = "WHERE $timeAttribute < $filterTimeValue";
        $insert = "INSERT INTO $logArchiveTable SELECT * FROM $logTable $where";
        $delete = "DELETE FROM $logTable $where";

        $sql = "$insert; $delete;";

        $transaction = Yii::$app->db->beginTransaction();

        try {
            $archivedLogs = Yii::$app->db->createCommand($sql)->execute();
            Yii::info("Archived '$logTable' $archivedLogs logs", self::class);

            $transaction->commit();
        } catch (\Throwable $e) {
            $transaction->rollBack();

            Yii::error($e->getMessage(), self::class);
        }
    }
}
