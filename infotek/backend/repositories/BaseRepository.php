<?php

namespace app\repositories;

use Yii;
use yii\db\Exception;
use yii\db\Query;

abstract class BaseRepository
{
    abstract protected static function getTableName(): string;

    /**
     * Вывод всех записей
     *
     * @param array $fields
     * @return array
     * @throws Exception
     */
    public static function findAll(array $fields = ['id', 'uuid']): array
    {
        $fieldsString = implode(', ', array_map(fn($field) => "`$field`", $fields));

        return Yii::$app->db->createCommand("
        SELECT $fieldsString FROM {{%" . static::getTableName() . "}} 
        WHERE deleted_at IS NULL
    ")->queryAll();
    }

    /**
     * Вывод записи по uuid
     *
     * @param string $uuid
     * @param array $fields
     * @return array|null
     * @throws Exception
     */
    public static function findOne(string $uuid, array $fields = ['id', 'uuid']): ?array
    {
        $fieldsString = implode(', ', array_map(fn($field) => "`$field`", $fields));

        return Yii::$app->db->createCommand("
                SELECT $fieldsString FROM {{%" . static::getTableName() . "}} WHERE uuid = :uuid AND deleted_at IS NULL
            ")
            ->bindValue(':uuid', $uuid)
            ->queryOne();
    }

    public static function findByData(array $data): ?array
    {
        $query = (new Query())
            ->from(static::getTableName());

        foreach ($data as $key => $value) {
            $query->andWhere([$key => $value]);
        }

        $record = $query->one(Yii::$app->db);

        return $record ?: null;
    }

    /**
     * Удаление записи по uuid
     *
     * @param string $uuid
     * @return bool
     * @throws Exception
     */
    public static function deleteByUuid(string $uuid): bool
    {
        return (bool) Yii::$app->db->createCommand('
                UPDATE {{%' . static::getTableName() . '}} SET deleted_at = CURRENT_TIMESTAMP WHERE uuid = :uuid
            ')
            ->bindValue(':uuid', $uuid)
            ->execute();
    }
}