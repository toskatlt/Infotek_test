<?php

use yii\db\Migration;

class m250310_000000_init extends Migration
{
    public function up()
    {
        $tableOptions = null;

        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%user}}', [
            'id' => $this->primaryKey(),
            'uuid' => $this->char(36)->notNull()->unique(),
            'username' => $this->string()->notNull()->unique(),
            'auth_key' => $this->string(32)->notNull(),
            'password_hash' => $this->string()->notNull(),
            'password_reset_token' => $this->string()->unique(),
            'verification_token' => $this->string()->defaultValue(null),
            'email' => $this->string()->notNull()->unique(),
            'phone' => $this->string(11)->notNull()->unique(),
            'send_sms' => $this->boolean()->defaultValue(0)->notNull(),
            'created_at' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP'),
            'updated_at' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'),
            'deleted_at' => $this->timestamp()->null(),
        ], $tableOptions);

        // Добавляем индексы
        $this->createIndex('idx-user-uuid', '{{%user}}', 'uuid');
        $this->createIndex('idx-user-username', '{{%user}}', 'username');
        $this->createIndex('idx-user-email', '{{%user}}', 'email');
        $this->createIndex('idx-user-phone', '{{%user}}', 'phone');
        $this->createIndex('idx-user-deleted_at', '{{%user}}', 'deleted_at');
    }

    public function down()
    {
        $this->dropIndex('idx-user-uuid', '{{%user}}');
        $this->dropIndex('idx-user-username', '{{%user}}');
        $this->dropIndex('idx-user-email', '{{%user}}');
        $this->dropIndex('idx-user-phone', '{{%user}}');
        $this->dropIndex('idx-user-deleted_at', '{{%user}}');

        $this->dropTable('{{%user}}');
    }
}
