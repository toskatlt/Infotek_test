<?php

namespace app\service;

use app\Models\Book;
use app\Repositories\BookRepository;
use app\Repositories\SubscriptionRepository;
use app\trait\Helpers;
use yii\db\Exception;
use yii\web\ForbiddenHttpException;
use yii\web\NotFoundHttpException;
use Yii;

class BookService
{
    use Helpers;

    /**
     * Вывод всех записей
     *
     * @throws Exception
     */
    public function findAll(): array
    {
        if (!Yii::$app->user->can('viewBooks')) {
            throw new Exception('Недостаточно прав для просмотра книг');
        }

        return BookRepository::findAll(['uuid', 'title', 'year', 'description', 'isbn']);
    }

    /**
     * Вывод записи по uuid
     *
     * @throws Exception
     */
    public function findOne(string $uuid): array
    {
        if (!Yii::$app->user->can('viewBooks')) {
            throw new Exception('Недостаточно прав для просмотра книги');
        }

        return BookRepository::findBooksByUuid($uuid);
    }

    /**
     * Вывод всех книг с авторами
     *
     * @throws Exception
     */
    public function findAllBooksAndAuthors(): array
    {
        if (!Yii::$app->user->can('viewBooks')) {
            throw new Exception('Недостаточно прав для просмотра книг');
        }

        return BookRepository::findAllBooksAndAuthors();
    }

    /**
     * @throws Exception
     */
    public function prepareBook(array $data): Book
    {
        $book = new Book();
        $book->uuid = self::uuid();
        $book->title = $data['title'] ?? null;
        $book->year = $data['year'] ?? null;
        $book->isbn = $data['isbn'] ?? null;
        $book->description = $data['description'] ?? null;

        if ($book->isbn !== null && BookRepository::findByData(['isbn' => $book->isbn])) {
            throw new Exception('Книга с таким ISBN уже существует');
        }

        if (!$book->validate()) {
            throw new Exception('Неверные данные');
        }

        return $book;
    }

    /**
     * Добавление книги
     *
     * @throws ForbiddenHttpException
     * @throws NotFoundHttpException|Exception
     */
    public function setBook(Book $book, array $authorUuids): bool
    {
        if (!Yii::$app->user->can('manageBooks')) {
            throw new ForbiddenHttpException('Недостаточно прав для добавления книги');
        }

        // toDo если у книги подразумевается несколько картинок, сделать отдельную таблицу

        try {
            $setBook = BookRepository::setBook($book);
            $authorService = new AuthorService();

            if ($authorUuids) {
                foreach ($authorUuids as $authorUuid) {

                    $authorData = $authorService->findOne($authorUuid);
                    if (!$authorData) {
                        throw new NotFoundHttpException('Автор не найден');
                    }

                    $authorService->setAuthorByBook($authorUuid, $book->uuid);

                    $subscriptionUsers = SubscriptionRepository::findAllSubscriptionsByAuthorUuid($authorUuid);
                    if (!empty($subscriptionUsers)) {

                        foreach ($subscriptionUsers as $subscriptionUser) {

                            // toDo сделать реализацию через очередь

                            $message = "Новая книга автора " . $authorData['name'] . " добавлена в библиотеку.";
                            $sendSms = (new SmsService())->sendSms($subscriptionUser['phone'], $message);

                            if (!$sendSms) {
                                Yii::error("Не удалось отправить SMS пользователю " . $subscriptionUser['user_uuid'] . ": " . $message, __METHOD__);
                            }
                        }
                    }
                }
            }
        } catch (Exception $e) {
            Yii::error("Ошибка в setBook: " . $e->getMessage(), __METHOD__);

            return false;
        }

        Yii::$app->session->setFlash('success', 'Книга успешно добавлена!');

        return true;
    }

    /**
     * Удаление книги
     *
     * @throws Exception
     * @throws NotFoundHttpException
     */
    public function deleteBook(string $uuid): bool
    {
        $book = $this->findOne($uuid);

        if (!$book) {
            throw new NotFoundHttpException('Книга не найдена');
        }

        return BookRepository::deleteByUuid($uuid);
    }
}