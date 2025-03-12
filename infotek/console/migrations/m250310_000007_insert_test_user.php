<?php

use yii\db\Migration;

class m250310_000007_insert_test_user extends Migration
{
    public function safeUp()
    {
        $this->batchInsert('{{%user}}',
            ['uuid', 'username', 'auth_key', 'password_hash', 'password_reset_token', 'verification_token', 'email', 'phone', 'send_sms', 'created_at', 'updated_at', 'deleted_at'],
            [
                ['36e388c5-c7d2-4a2e-af08-0b364ec23512', 'user1', Yii::$app->security->generateRandomString(), Yii::$app->security->generatePasswordHash('password1'), null, null, 'user1@example.com', '71234567890', 1, date('Y-m-d H:i:s'), date('Y-m-d H:i:s'), null],
                ['cd165080-c6e7-4aed-8949-f7d7a2dd950e', 'user2', Yii::$app->security->generateRandomString(), Yii::$app->security->generatePasswordHash('password2'), null, null, 'user2@example.com', '70987654321', 1, date('Y-m-d H:i:s'), date('Y-m-d H:i:s'), null],
                ['00f9cb06-d4eb-49e3-a635-8de75e3f8a61', 'user3', Yii::$app->security->generateRandomString(), Yii::$app->security->generatePasswordHash('password3'), null, null, 'user3@example.com', '71122334455', 0, date('Y-m-d H:i:s'), date('Y-m-d H:i:s'), null],
                ['b5aaf4bc-696e-425f-80e1-adad306f4f69', 'user4', Yii::$app->security->generateRandomString(), Yii::$app->security->generatePasswordHash('password4'), null, null, 'user4@example.com', '75566778899', 0, date('Y-m-d H:i:s'), date('Y-m-d H:i:s'), null],
            ]
        );
    }

    public function safeDown()
    {
        $this->delete('{{%user}}', ['username' => ['user1', 'user2', 'user3', 'user4']]);
    }
}