<?php

namespace app\service;

use app\repositories\AuthorRepository;
use app\repositories\BookAuthorRepository;
use app\repositories\BookRepository;
use Yii;
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

    /**
     * @throws Exception
     */
    public function getTopAuthorsByYear(int $year): array
    {
        return AuthorRepository::getTopAuthorsByYear($year);
    }

    /**
     * Обновление авторов книги
     *
     * @param string $bookUuid - uuid книги
     * @param array $authors - массив авторов
     */
    public function updateAuthorByBook(string $bookUuid, array $authors): bool
    {
        $transaction = Yii::$app->db->beginTransaction();

        try {
            $this->deleteAuthorByBook($bookUuid);

            foreach ($authors as $author) {
                $this->setAuthorByBook($author, $bookUuid);
            }

            $transaction->commit();
        } catch (Exception $e) {
            $transaction->rollBack();
            Yii::error("Ошибка в updateAuthorByBook: " . $e->getMessage(), __METHOD__);

            return false;
        }

        return true;
    }


    /**
     * Удаление авторов книги
     *
     * @param string $bookUuid
     * @return bool
     */
    public function deleteAuthorByBook(string $bookUuid): bool
    {
        return BookAuthorRepository::deleteAuthorByBook($bookUuid);
    }

    /**
     * Возвращает список возможных годов
     *
     * @throws Exception
     */
    public function getYears(): array
    {
        $years = BookRepository::getYears();

        return array_column($years, 'year');
    }
}