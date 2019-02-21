<?php

use common\models\User;

/* @var $directoryAsset string */
?>

<aside class="main-sidebar">

    <section class="sidebar">

        <?php
        $menuItems = [];

        $menuItems[] = ['label' => 'Menu', 'options' => ['class' => 'header']];

        if (Yii::$app->user->can(User::ROLE_MEMBER)) {
            $menuItems[] = ['label' => 'Home', 'icon' => 'home', 'url' => ['/']];

            // TODO add logs
            $menuItems[] = ['label' => 'Server logs', 'icon' => 'server', 'url' => ['/logs/log/index']];

            if (Yii::$app->user->can(User::ROLE_ADMIN)) {
                // TODO add archive
                $menuItems[] = ['label' => 'Logs archive', 'icon' => 'archive', 'url' => '#', 'items' => [
                    ['label' => 'Server logs archive', 'icon' => 'server', 'url' => ['/logs/log-archive/index']],
                ]];
                $menuItems[] = ['label' => 'Users', 'icon' => 'users', 'url' => ['/user/index']];
            }

            $menuItems[] = ['label' => 'Api docs runner', 'icon' => 'book', 'url' => ['/documentation']];

            $menuItems[] = [
                'label' => 'Docs',
                'url' => ['/doc/page', 'view' => 'guide-README.html'],
                'template' => '<a href="{url}" target="_blank">
                        <i class="fa fa-external-link"></i>
                        {label}
                    </a>'
            ];
        }

        ?>

        <?= dmstr\widgets\Menu::widget(
            [
                'options' => ['class' => 'sidebar-menu tree', 'data-widget'=> 'tree'],
                'items' => $menuItems,
            ]
        ) ?>

    </section>

</aside>
