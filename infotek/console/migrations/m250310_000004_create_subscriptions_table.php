<?php

use yii\db\Migration;

class m250310_000004_create_subscriptions_table extends Migration
{
    public function safeUp()
    {
        $this->createTable('subscriptions', [
            'id' => $this->primaryKey(),
            'user_uuid' => $this->char(36)->notNull(),
            'author_uuid' => $this->char(36)->notNull(),
            'created_at' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP'),
            'deleted_at' => $this->timestamp()->null(),
        ]);

        // Индексы для ускоренного поиска
        $this->createIndex('idx-subscriptions-user_uuid', 'subscriptions', 'user_uuid');
        $this->createIndex('idx-subscriptions-author_uuid', 'subscriptions', 'author_uuid');
    }

    public function safeDown()
    {
        $this->dropIndex('idx-subscriptions-user_uuid', 'subscriptions');
        $this->dropIndex('idx-subscriptions-author_uuid', 'subscriptions');
        $this->dropTable('subscriptions');
    }
}