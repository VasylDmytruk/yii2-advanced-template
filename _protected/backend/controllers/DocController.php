<?php

namespace backend\controllers;

use yii\web\ViewAction;

/**
 * Class DocController Provides secure access to generated docs.
 */
class DocController extends BackendController
{
    /**
     * @inheritdoc
     */
    public function actions()
    {
        return [
            'page' => [
                'class' => ViewAction::class,
                'layout' => false,
            ],
        ];
    }

    /**
     * Redirects to needed api doc view (to page action).
     *
     * @param string $view
     *
     * @return \yii\web\Response
     */
    public function actionView($view)
    {
        return $this->redirect(['page', 'view' => $view]);
    }
}
