<?php

namespace app\service;

use app\repositories\AuthorRepository;
use app\repositories\BookAuthorRepository;
use yii\db\Exception;

class AuthorService
{
    /**
     * Вывод всех записей
     *
     * @throws Exception
     */
    public function findAll(): array
    {
        return AuthorRepository::findAll(['uuid', 'name']);
    }

    /**
     * Вывод записи по uuid
     *
     * @throws Exception
     */
    public function findOne(string $uuid): array
    {
        return AuthorRepository::findOne($uuid, ['uuid', 'name']);
    }

    /**
     * Вывод авторов книги
     *
     */
    public function getAuthorsByBookUuid(string $bookUuid): array
    {
        return AuthorRepository::getAuthorsByBookUuid($bookUuid);
    }

    /**
     * @throws Exception
     */
    public function setAuthorByBook(string $authorUuid, string $bookUuid): bool
    {
        return BookAuthorRepository::setAuthorByBook($authorUuid, $bookUuid);
    }
}