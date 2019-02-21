<?php

namespace backend\modules\logs\controllers;

use common\models\logs\Log;
use common\models\logs\LogSearch;
use Yii;
use yii\base\InvalidConfigException;
use yii\helpers\ArrayHelper;
use yii\web\NotFoundHttpException;

/**
 * LogController implements the CRUD actions for Log model.
 */
class LogController extends BaseLogController
{
    /**
     * @var string|Log
     */
    protected $logModel = Log::class;
    /**
     * @var string|LogSearch
     */
    protected $logSearchModel = LogSearch::class;
    /**
     * @var string
     */
    protected $indexTitle = 'Server logs';


    /**
     * @inheritdoc
     * @throws InvalidConfigException
     */
    public function init()
    {
        parent::init();

        if ($this->logModel !== Log::class && !is_subclass_of($this->logModel, Log::class)) {
            throw new InvalidConfigException('Param "logModel" must be class or subclass of ' . Log::class);
        }

        if ($this->logSearchModel !== LogSearch::class && !is_subclass_of($this->logSearchModel, LogSearch::class)) {
            throw new InvalidConfigException('Param "logSearchModel" must be class or subclass of ' . LogSearch::class);
        }
    }

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return ArrayHelper::merge(parent::behaviors(), []);
    }

    /**
     * Lists all Log models.
     * @return mixed
     */
    public function actionIndex()
    {
        /* @var $searchModel LogSearch */
        $searchModel = new $this->logSearchModel();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        $levels = $this->logModel::levels();
        $categories = $this->logModel::findMappedCategoriesAsArrayAll();

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'levels' => $levels,
            'categories' => $categories,
            'title' => $this->indexTitle,
        ]);
    }

    /**
     * Displays a single Log model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Finds the Log model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Log the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = $this->logModel::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }
}
