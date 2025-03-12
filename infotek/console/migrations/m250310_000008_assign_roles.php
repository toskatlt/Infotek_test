<?php

use yii\db\Migration;

class m250310_000008_assign_roles extends Migration
{
    public function safeUp()
    {
        $auth = Yii::$app->authManager;

        $guestRole = $auth->getRole('guest');
        $userRole = $auth->getRole('user');

        $auth->assign($guestRole, 1);
        $auth->assign($userRole, 2);
        $auth->assign($guestRole, 3);
        $auth->assign($userRole, 4);
    }

    public function safeDown()
    {
        $auth = Yii::$app->authManager;

        $auth->revokeAll(1);
        $auth->revokeAll(2);
        $auth->revokeAll(3);
        $auth->revokeAll(4);
    }
}