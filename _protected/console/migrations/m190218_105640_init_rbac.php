<?php

use yii\db\Migration;
use yii\helpers\Console;

/**
 * Class m190218_105640_init_rbac Inits default roles of app.
 */
class m190218_105640_init_rbac extends Migration
{
    // TODO add m190218_105640_init_rbac | 1592747410 to table migrations!!!!!!!!!!!
    public function safeUp()
    {
        $auth = Yii::$app->authManager;

        //---------- ROLES ----------//

        // add "member" role
        $member = $auth->createRole('member');
        $member->description = 'Authenticated user, equal to "@"';
        $auth->add($member);

        // add "admin" role and give this role:
        // manageUsers, updateArticle adn deleteArticle permissions, plus employee role.
        $admin = $auth->createRole('admin');
        $admin->description = 'Administrator of this application';
        $auth->add($admin);
        $auth->addChild($admin, $member);

        // add "theCreator" role ( this is you :) )
        // You can do everything that admin can do plus more (if You decide so)
        $theCreator = $auth->createRole('theCreator');
        $theCreator->description = 'You!';
        $auth->add($theCreator);
        $auth->addChild($theCreator, $admin);

        if ($auth) {
            Console::stdout("\nRbac authorization data are installed successfully.\n", Console::FG_GREEN);
        }
    }

    public function safeDown()
    {
        $auth = Yii::$app->authManager;

        $member = $auth->getRole('member');
        $admin = $auth->getRole('admin');
        $theCreator = $auth->getRole('theCreator');

        if ($member && $admin) {
            $auth->removeChild($admin, $member);
            $auth->remove($member);
        }

        if ($admin && $theCreator) {
            $auth->removeChild($theCreator, $admin);
            $auth->remove($admin);
        }

        if ($theCreator) {
            $auth->remove($theCreator);
        }
    }
}
