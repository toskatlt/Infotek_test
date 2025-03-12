<?php

use yii\db\Migration;

class m250310_000002_create_authors_table extends Migration
{
    public function safeUp(): void
    {
        $this->createTable('authors', [
            'id' => $this->primaryKey(),
            'uuid' => $this->char(36)->notNull()->unique(),
            'name' => $this->string(255)->notNull(),
            'created_at' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP'),
            'updated_at' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'),
            'deleted_at' => $this->timestamp()->null(),
        ]);

        $this->createIndex('idx-authors-uuid', 'authors', 'uuid');
    }

    public function safeDown(): void
    {
        $this->dropTable('authors');
    }
}