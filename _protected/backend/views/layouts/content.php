<?php
use yii\widgets\Breadcrumbs;
use dmstr\widgets\Alert;

?>
<div class="content-wrapper">
    <section class="content-header-custom">
        <?php /*if (isset($this->blocks['content-header'])) { */?><!--
            <h1><?php /*echo $this->blocks['content-header']; */?></h1>
        <?php /*} else { */?>
            <h1>
                <?php
/*                if ($this->title !== null) {
                    echo \yii\helpers\Html::encode($this->title);
                } else {
                    echo \yii\helpers\Inflector::camel2words(
                        \yii\helpers\Inflector::id2camel($this->context->module->id)
                    );
                    echo ($this->context->module->id !== \Yii::$app->id) ? '<small>Module</small>' : '';
                } */?>
            </h1>
        --><?php /*} */?>

        <?= \yii\widgets\Breadcrumbs::widget(['links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : []]) ?>

    </section>

    <section class="content">

        <?php
        if (Yii::$app->session->getAllFlashes()) {
            echo \yii\helpers\Html::tag('div', Alert::widget(), ['class' => 'margin-top-15']);
        } ?>

        <?= $content ?>
    </section>
</div>

<footer class="main-footer">
    <div class="pull-right hidden-xs">
        <b>Version</b> 1.0
    </div>
    <strong>Copyright &copy; <?= date("Y"); ?> <a href="https://webxloo.com/" target="_blank">Webxloo</a>.</strong> All rights reserved.
</footer>
