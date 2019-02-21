<?php

use common\models\logs\Log;
use common\widgets\grid\GridView;
use kartik\datecontrol\DateControl;
use yii\bootstrap\Modal;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $searchModel \common\models\logs\LogSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
/* @var $levels array */
/* @var $categories array */
/* @var $title string */

$this->title = $title;
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="log-index">

    <h3><?= Html::encode($this->title) ?></h3>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>
    <!-- columns width is set on backend when echos GridView -->

    <?php $messagesModalContent = []; ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            [
                'attribute' => 'level',
                'value' => function (Log $model) {
                    return $model->getLevel_label();
                },
                'filter' => $levels,
                'options' => ['width' => '8%'],
            ],
            [
                'attribute' => 'category',
                'filter' => \kartik\select2\Select2::widget([
                    'data' => $categories,
                    'model' => $searchModel,
                    'attribute' => 'category',
                    'options' => ['placeholder' => 'Select...'],
                    'pluginOptions' => [
                        'allowClear' => true,
                        'dropdownAutoWidth' => true,
                    ],
                ]),
            ],
            [
                'attribute' => 'log_time',
                'format' => 'datetime',
                'filter' => \kartik\daterange\DateRangePicker::widget([
                    'model' => $searchModel,
                    'attribute' => 'log_time_search',
                    'convertFormat' => true,
                    'pluginOptions' => [
                        'timePicker' => true,
                        'timePickerIncrement' => 1,
                        'locale' => ['format' => Yii::$app->formatter->rawDatetimeFormat],
                    ],
                ]),
            ],
            [
                'attribute' => 'message',
                'format' => 'raw',
                'options' => ['width' => '55%'],
                'value' => function (Log $model) use (&$messagesModalContent) {

                    $messagesModalContent[$model->id] = $model->message;

                    $messageToShow = str_replace("\n\n", '', $model->message);
                    $messageToShow = str_replace("\n", '; ', $messageToShow);

                    if (strlen($messageToShow) > Log::MAX_MESSAGE_LENGTH) {
                        $messageToShow = substr($messageToShow, 0, Log::MAX_MESSAGE_LENGTH) . '...';
                    }

                    return Html::tag('span', $messageToShow, [
                        'data' => [
                            'toggle' => 'modal',
                            'target' => '#' . $model->id,
                        ],
                        'class' => 'cursor-pointer',
                    ]);
                },
            ],
            [
                'class' => 'yii\grid\ActionColumn',
                'template' => '{view}',
                'options' => ['width' => '3%'],
            ],
        ],
    ]); ?>

    <?php
    foreach ($messagesModalContent as $messageId => $messageContent) {
        Modal::begin([

            'id' => $messageId,
            'header' => '<h3>Details</h3>',
            'options' => ['class' => 'ajax-modal-wrap'],
        ]);

        $afterHtmlspecialchars = htmlspecialchars($messageContent);
        echo '<div class="break-word-all">' . nl2br($afterHtmlspecialchars) . '</div>';

        Modal::end();
    }
    ?>

</div>
