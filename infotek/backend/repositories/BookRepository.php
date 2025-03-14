<?php

namespace app\repositories;

use app\Models\Book;
use Yii;
use yii\db\Exception;

class BookRepository extends BaseRepository
{
    private const TABLE_NAME = 'books';

    protected static function getTableName(): string
    {
        return self::TABLE_NAME;
    }

    /**
     * Вывод всех записей с авторами
     *
     * @return array
     * @throws Exception
     */
    public static function findAllBooksAndAuthors(): array
    {
        return Yii::$app->db->createCommand('
                SELECT 
                    b.uuid as uuid, 
                    b.title as title,
                    b.year as year, 
                    b.description as description, 
                    b.isbn as isbn, 
                    GROUP_CONCAT(a.name ORDER BY a.name SEPARATOR ", ") AS authors_name
                FROM {{%' . static::getTableName() . '}} b
                LEFT JOIN book_author ba ON ba.book_uuid = b.uuid
                LEFT JOIN authors a ON a.uuid = ba.author_uuid
                WHERE b.deleted_at IS NULL
                GROUP BY b.uuid, b.title
            ')->queryAll();
    }

    /**
     * Вывод записи по uuid
     *
     * @param string $uuid
     * @return array|null
     * @throws Exception
     */
    public static function findBooksByUuid(string $uuid): ?array
    {
        return Yii::$app->db->createCommand('
                SELECT 
                    b.uuid, 
                    b.title, 
                    b.year, 
                    b.description, 
                    b.isbn, 
                    GROUP_CONCAT(a.name ORDER BY a.name SEPARATOR ", ") AS authors_name
                FROM {{%' . static::getTableName() . '}} b
                LEFT JOIN book_author ba ON ba.book_uuid = b.uuid
                LEFT JOIN authors a ON a.uuid = ba.author_uuid
                WHERE b.uuid = :uuid AND b.deleted_at IS NULL
                GROUP BY b.uuid, b.title, b.year, b.description, b.isbn
            ')
            ->bindValue(':uuid', $uuid)
            ->queryOne();
    }

    /**
     * Добавление книги
     *
     * @param Book $book
     * @return bool
     * @throws Exception
     */
    public static function setBook(Book $book): bool
    {
        return Yii::$app->db->createCommand('
                INSERT INTO ' . self::TABLE_NAME . ' (uuid, title, year, description, isbn) 
                VALUES (:uuid, :title, :year, :description, :isbn)
            ')
            ->bindValue(':uuid', $book->uuid)
            ->bindValue(':title', $book->title)
            ->bindValue(':year', $book->year)
            ->bindValue(':description', $book->description)
            ->bindValue(':isbn', $book->isbn)
            ->execute();
    }

    /**
     * Обновление книги
     *
     * @throws Exception
     */
    public static function updateBook(Book $book): bool
    {
        return Yii::$app->db->createCommand('
                UPDATE ' . self::TABLE_NAME . ' 
                SET 
                    title = :title, 
                    year = :year, 
                    description = :description, 
                    isbn = :isbn 
                WHERE 
                    uuid = :uuid
            ')
            ->bindValue(':uuid', $book->uuid)
            ->bindValue(':title', $book->title)
            ->bindValue(':year', $book->year)
            ->bindValue(':description', $book->description)
            ->bindValue(':isbn', $book->isbn)
            ->execute();
    }

    /**
     * Массив возможных вариантов годов выпуска книг
     *
     * @return array
     * @throws Exception
     */
    public static function getYears(): array
    {
        return Yii::$app->db->createCommand('
                SELECT DISTINCT year
                FROM ' . self::TABLE_NAME . '
                WHERE deleted_at IS NULL
                ORDER BY year DESC
            ')
            ->queryAll();
    }
}