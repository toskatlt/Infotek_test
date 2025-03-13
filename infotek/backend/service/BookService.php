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
        $book->uuid = $data['uuid'] ?? self::uuid();
        $book->title = isset($data['title']) ? trim($data['title']) : null;
        $book->year = isset($data['year']) ? (int)$data['year'] : null;
        $book->isbn = isset($data['isbn']) ? trim($data['isbn']) : null;
        $book->description = isset($data['description']) ? trim($data['description']) : null;
        $book->authors = $data['authors'] ?? [];

        if (!$book->validate()) {
            throw new Exception('Неверные данные: ' . json_encode($book->errors));
        }

        return $book;
    }

    /**
     * Добавление книги
     *
     * @throws NotFoundHttpException|
     * @throws Exception
     */
    public function setBook(Book $book): bool
    {
        // toDo если у книги подразумевается несколько картинок, сделать отдельную таблицу

        if ($book->isbn !== null && BookRepository::findByData(['isbn' => $book->isbn, 'deleted_at' => null])) {
            throw new Exception('Книга с таким ISBN уже существует');
        }

        try {
            BookRepository::setBook($book);
            $authorService = new AuthorService();

            if ($book->authors) {
                foreach ($book->authors as $authorUuid) {

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
     * Обновление книги
     *
     */
    public function updateBook(Book $book): bool
    {
        try {
            BookRepository::updateBook($book);
            $authorService = new AuthorService();
            $authorService->updateAuthorByBook($book->uuid, $book->authors);
        } catch (Exception $e) {
            Yii::error("Ошибка в updateBook: " . $e->getMessage(), __METHOD__);

            return false;
        }

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