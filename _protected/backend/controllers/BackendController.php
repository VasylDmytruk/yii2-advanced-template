<?php
namespace backend\controllers;

use common\models\User;
use yii\web\Controller;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use Yii;

/**
 * BackendController extends Controller and implements the behaviors() method
 * where you can specify the access control ( AC filter + RBAC) for 
 * your controllers and their actions.
 */
class BackendController extends Controller
{
    /**
     * Returns a list of behaviors that this component should behave as.
     * Here we use RBAC in combination with AccessControl filter.
     *
     * @return array
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'rules' => [
                    [
                        'controllers' => ['user', 'logs/log', 'logs/log-archive'],
                        'actions' => ['index', 'view', 'create', 'update', 'delete'],
                        'allow' => true,
                        'roles' => [User::ROLE_ADMIN],
                    ],
                    [
                        'controllers' => ['logs/log'],
                        'actions' => ['index', 'view'],
                        'allow' => true,
                        'roles' => [User::ROLE_MEMBER],
                    ],
                    [
                        'controllers' => ['doc'],
                        'actions' => ['page', 'view'],
                        'allow' => true,
                        'roles' => [User::ROLE_MEMBER],
                    ],

                ], // rules

            ], // access

            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'delete' => ['post'],
                ],
            ], // verbs

        ]; // return

    } // behaviors

} // BackendController