<?php

use yii\db\Migration;

class m250310_000001_create_books_table extends Migration
{
    public function safeUp()
    {
        $this->createTable('books', [
            'id' => $this->primaryKey(),
            'uuid' => $this->char(36)->notNull()->unique(),
            'title' => $this->string(255)->notNull(),
            'year' => $this->integer()->notNull(),
            'description' => $this->text(),
            'isbn' => $this->string(20)->unique(),
            'created_at' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP'),
            'updated_at' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'),
            'deleted_at' => $this->timestamp()->null(),
        ]);

        // Добавление индекса на uuid
        $this->createIndex('idx-books-uuid', 'books', 'uuid');
    }

    public function safeDown()
    {
        $this->dropIndex('idx-books-uuid', 'books');
        $this->dropTable('books');
    }
}