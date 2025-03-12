<?php

namespace app\repositories;

use app\models\Subscription;
use yii\db\Exception;
use Yii;

class SubscriptionRepository extends BaseRepository
{
    private const TABLE_NAME = 'subscriptions';

    protected static function getTableName(): string
    {
        return self::TABLE_NAME;
    }

    /**
     * Проверяет, существует ли подписка
     *
     * @param string $userUuid - uuid пользователя
     * @param string $authorUuid - uuid автора
     * @return bool
     * @throws Exception
     */
    public static function exists(string $userUuid, string $authorUuid): bool
    {
        return (bool) Yii::$app->db->createCommand('
                SELECT COUNT(*) 
                FROM {{%' . self::TABLE_NAME . '}} 
                WHERE user_uuid = :user_uuid 
                AND author_uuid = :author_uuid 
                AND deleted_at IS NULL
            ')
            ->bindValue(':user_uuid', $userUuid)
            ->bindValue(':author_uuid', $authorUuid)
            ->queryScalar();
    }

    /**
     * Вернуть всех пользователей подписанных на автора, которые ждут оповещение
     *
     * @param string $authorUuid - uuid авторa
     * @throws Exception
     */
    public static function findAllSubscriptionsByAuthorUuid(string $authorUuid): ?array
    {
        return Yii::$app->db->createCommand('
            SELECT 
                u.uuid as user_uuid,
                u.phone as phone
            FROM {{%' . static::getTableName() . '}} s
            JOIN {{%user}} u ON s.user_uuid = u.uuid
            WHERE s.author_uuid = :uuid
              AND u.deleted_at IS NULL
              AND u.send_sms = 1
        ')
            ->bindValue(':uuid', $authorUuid)
            ->queryAll();
    }

    /**
     * Вернуть всех авторов на которых подписан пользователь
     *
     * @param string $userUuid - uuid пользователя
     * @throws Exception
     */
    public static function findAllAuthorsByUserUuid(string $userUuid): ?array
    {
        return Yii::$app->db->createCommand('
            SELECT 
                author_uuid
            FROM {{%' . static::getTableName() . '}}
            WHERE user_uuid = :uuid
            AND deleted_at IS NULL
        ')
            ->bindValue(':uuid', $userUuid)
            ->queryAll();
    }

    /**
     * Сохраняет подписку
     *
     * @param Subscription $subscription
     * @return bool
     * @throws Exception
     */
    public static function save(Subscription $subscription): bool
    {
        return (bool) Yii::$app->db->createCommand()->insert(self::TABLE_NAME, [
            'user_uuid' => $subscription->user_uuid,
            'author_uuid' => $subscription->author_uuid,
            'created_at' => date('Y-m-d H:i:s'),
            'deleted_at' => null,
        ])->execute();
    }
}
