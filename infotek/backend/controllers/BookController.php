<?php

namespace app\controllers;

use app\service\AuthorService;
use app\service\BookService;
use yii\db\Exception;
use yii\web\ForbiddenHttpException;
use yii\web\NotFoundHttpException;
use yii\web\Response;
use app\models\Book;
use Yii;

class BookController extends BaseController
{
    private BookService $bookService;

    public function __construct($id, $module, BookService $BookService, $config = [])
    {
        parent::__construct($id, $module, $config);

        $this->bookService = $BookService;
    }

    /**
     * @throws Exception
     */
    public function actionIndex(): string
    {
        return $this->render('index', [
            'books' => $this->bookService->findAllBooksAndAuthors()
        ]);
    }

    /**
     * @throws NotFoundHttpException
     * @throws Exception
     */
    public function actionView(string $uuid): string
    {
        $book = $this->bookService->findOne($uuid);

        if (!$book) {
            throw new NotFoundHttpException('Книга не найдена');
        }

        return $this->render('view', [
            'book' => $book
        ]);
    }

    /**
     * @throws Exception
     * @throws ForbiddenHttpException
     * @throws \yii\base\Exception
     */
    public function actionCreate(): string
    {
        if (Yii::$app->request->isPost) {
            $data = Yii::$app->request->post();
            $book = $this->bookService->prepareBook($data);
            $authors = $data['authors'] ?? [];

            $this->bookService->setBook($book, $authors);
        }

        $authors = (new AuthorService())->findAll();

        return $this->render('create', [
            'authors' => $authors,
        ]);
    }

    /**
     * @throws Exception
     * @throws NotFoundHttpException
     */
    public function actionDelete(string $uuid): Response
    {
        $result = $this->bookService->deleteBook($uuid);

        return $this->jsonResponse(['success' => $result]);
    }
}