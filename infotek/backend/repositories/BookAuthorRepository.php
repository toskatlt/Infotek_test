<?php

namespace App\repositories;

use Yii;
use yii\db\Exception;

class BookAuthorRepository extends BaseRepository
{
    private const TABLE_NAME = 'book_author';

    protected static function getTableName(): string
    {
        return self::TABLE_NAME;
    }

    /**
     * Добавление записи
     *
     * @param string $authorUuid
     * @param string $bookUuid
     * @return bool
     * @throws Exception
     */
    public static function setAuthorByBook(string $authorUuid, string $bookUuid): bool
    {
        return Yii::$app->db->createCommand('
            INSERT INTO ' . self::TABLE_NAME . ' (book_uuid, author_uuid) 
            VALUES (:book_uuid, :author_uuid)
        ')
            ->bindValue(':book_uuid', $bookUuid)
            ->bindValue(':author_uuid', $authorUuid)
            ->execute();
    }

    /**
     * Удаление связей автора и книги
     *
     * @throws Exception
     */
    public static function deleteAuthorByBook(string $bookUuid): bool
    {
        return Yii::$app->db->createCommand('
            DELETE FROM ' . self::TABLE_NAME . ' 
            WHERE book_uuid = :book_uuid
        ')
            ->bindValue(':book_uuid', $bookUuid)
            ->execute();
    }
}