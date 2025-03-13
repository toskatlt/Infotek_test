<?php

namespace app\repositories;

use Yii;
use yii\db\Exception;

class AuthorRepository extends BaseRepository
{
    private const TABLE_NAME = 'authors';

    protected static function getTableName(): string
    {
        return self::TABLE_NAME;
    }

    /**
     * Вывод всех авторов и их книги
     *
     * @return array
     * @throws Exception
     */
    public static function getAllAuthorsAndBooks(): array
    {
        return Yii::$app->db->createCommand('
            SELECT 
                a.uuid AS author_uuid, 
                a.name AS author_name, 
                b.uuid AS book_uuid, 
                b.title AS book_title
            FROM {{%authors}} a
            LEFT JOIN {{%book_author}} ba ON a.uuid = ba.author_uuid
            LEFT JOIN {{%books}} b ON ba.book_uuid = b.uuid
        ')->queryAll();
    }

    public static function getAuthorsByBookUuid(string $bookUuid): array
    {
        return Yii::$app->db->createCommand('
                SELECT 
                    a.uuid AS author_uuid, 
                    a.name AS author_name, 
                    b.uuid AS book_uuid, 
                    b.title AS book_title
                FROM {{%authors}} a
                LEFT JOIN {{%book_author}} ba ON a.uuid = ba.author_uuid
                LEFT JOIN {{%books}} b ON ba.book_uuid = b.uuid
                WHERE b.uuid = :book_uuid
            ')
            ->bindValue(':book_uuid', $bookUuid)
            ->queryAll();
    }

    /**
     * @throws Exception
     */
    public static function getTopAuthorsByYear(int $year, int $limit = 10): array
    {
        return Yii::$app->db->createCommand('
                SELECT 
                    a.name AS author_name,
                    COUNT(b.uuid) AS book_count
                FROM {{%authors}} a
                LEFT JOIN {{%book_author}} ba ON a.uuid = ba.author_uuid
                LEFT JOIN {{%books}} b ON ba.book_uuid = b.uuid
                WHERE b.year = :year
                AND b.deleted_at IS NULL
                GROUP BY a.uuid
                ORDER BY book_count DESC
                LIMIT :limit
            ')
            ->bindValue(':year', $year)
            ->bindValue(':limit', $limit)
            ->queryAll();
    }
}