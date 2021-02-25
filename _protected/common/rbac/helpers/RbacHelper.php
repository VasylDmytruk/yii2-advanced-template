<?php

namespace common\rbac\helpers;

use common\models\User;
use Yii;

/**
 * RBAC helper class.
 */
class RbacHelper
{
    /**
     * In development environment we want to give theCreator role to the first signed up user.
     * This user should be You.
     * If user is not first, there is no need to automatically give him role, his role is authenticated user '@'.
     * In case you want to give some of your custom roles to users by default, this is a good place to do it.
     *
     * @param integer $userId The id of the registered user.
     * @return boolean     True if theCreator role is assigned or if there was no need to do it.
     * @throws \Exception
     */
    public static function assignRole($userId)
    {
        // lets see how many users we got so far
        $usersCount = (int)User::find()->count();

        // if this is not first user, we do not want to assign any custom roles to him,
        // he has the authenticated role '@' by default, so there is no need to do anything
        if ($usersCount !== 1) {
            $memberAssignmentResult = self::assign($userId, User::ROLE_MEMBER);

            return $memberAssignmentResult;
        }

        // this is first user ( you ), lets give you the theCreator role
        $theCreatorAssignmentResult = self::assign($userId, User::ROLE_CREATOR);

        return $theCreatorAssignmentResult;
    }

    /**
     * @param int $userId
     * @param string $roleName
     * @return bool If assignment was successful return true, else return false to alarm the problem
     * @throws \Exception
     */
    protected static function assign(int $userId, string $roleName): bool
    {
        $auth = Yii::$app->authManager;
        $role = $auth->getRole($roleName);
        $info = $auth->assign($role, $userId);

        return ($info->roleName === $roleName);
    }
}

