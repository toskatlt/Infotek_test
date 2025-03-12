<?php

use yii\db\Migration;

class m250310_000003_create_book_author_table extends Migration
{
    public function safeUp()
    {
        $this->createTable('book_author', [
            'book_uuid' => $this->char(36)->notNull(),
            'author_uuid' => $this->char(36)->notNull(),
        ]);

        $this->addPrimaryKey('pk-book-author', 'book_author', ['book_uuid', 'author_uuid']);

        // Индексы для ускоренного поиска
        $this->createIndex('idx-book-author-book_uuid', 'book_author', 'book_uuid');
        $this->createIndex('idx-book-author-author_uuid', 'book_author', 'author_uuid');

        // Связи с таблицами books и authors по uuid
        $this->addForeignKey('fk-book_author-book', 'book_author', 'book_uuid', 'books', 'uuid', 'CASCADE');
        $this->addForeignKey('fk-book_author-author', 'book_author', 'author_uuid', 'authors', 'uuid', 'CASCADE');
    }

    public function safeDown()
    {
        $this->dropForeignKey('fk-book_author-book', 'book_author');
        $this->dropForeignKey('fk-book_author-author', 'book_author');
        $this->dropIndex('idx-book-author-book_uuid', 'book_author');
        $this->dropIndex('idx-book-author-author_uuid', 'book_author');
        $this->dropTable('book_author');
    }
}