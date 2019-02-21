<?php

namespace frontend\filters;

use Yii;
use yii\base\ActionFilter;
use yii\helpers\Url;

/**
 * Class AdminRedirectFilter
 */
class AdminRedirectFilter extends ActionFilter
{
    public $adminDir = 'admin';


    /**
     * @param \yii\base\Action $action
     * @return bool|AdminRedirectFilter|\yii\console\Response|\yii\web\Response
     */
    public function beforeAction($action)
    {
        // Redirect everything to admin
        return Yii::$app->response->redirect(Url::base(true) . '/' . $this->adminDir);
    }
}
