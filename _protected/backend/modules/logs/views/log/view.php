<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model \common\models\logs\Log */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Server logs'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="log-view">

    <h3>
        <?= Html::encode($model->level_label . ': ' . $this->title) ?>

        <div class="pull-right back-btn">
            <?= Html::a(Yii::t('app', 'Back'), ['index'], ['class' => 'btn btn-warning']) ?>
        </div>
    </h3>

    <?= \common\widgets\detail\DetailView::widget([
        'model' => $model,
        'columnGroupsOptions' => [
            0 => ['width' => '150px']
        ],
        'attributes' => [
            'id',
            'level_label',
            'category',
            'log_time:datetime',
            'prefix:ntext',
            'message:ntext',
        ],
    ]) ?>

</div>
