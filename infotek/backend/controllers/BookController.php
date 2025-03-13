<?php

namespace app\controllers;

use app\service\AuthorService;
use app\service\BookService;
use yii\db\Exception;
use yii\web\ForbiddenHttpException;
use yii\web\NotFoundHttpException;
use Yii;

class BookController extends BaseController
{
    private BookService $bookService;
    private AuthorService $authorService;

    public function __construct(
        $id,
        $module,
        AuthorService $AuthorService,
        BookService $BookService,
        $config = [])
    {
        parent::__construct($id, $module, $config);
        $this->authorService = $AuthorService;
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
        if (!Yii::$app->user->can('manageBooks')) {
            throw new ForbiddenHttpException('Недостаточно прав для добавления книги');
        }

        if (Yii::$app->request->isPost) {
            $data = Yii::$app->request->post();
            $book = $this->bookService->prepareBook($data);
            $this->bookService->setBook($book);
        }

        $authors = $this->authorService->findAll();

        return $this->render('create', [
            'authors' => $authors,
        ]);
    }

    /**
     * @throws Exception
     * @throws NotFoundHttpException
     * @throws ForbiddenHttpException
     */
    public function actionUpdate(string $uuid): string
    {
        if (!Yii::$app->user->can('manageBooks')) {
            throw new ForbiddenHttpException('Недостаточно прав для редактирования книги');
        }

        if (Yii::$app->request->isPost) {
            $data = Yii::$app->request->post();

            if (!$this->bookService->findOne($uuid)) {
                throw new NotFoundHttpException('Книга не найдена');
            }
            $bookData = $this->bookService->prepareBook($data);
            $this->bookService->updateBook($bookData);
        }

        $book = $this->bookService->findOne($uuid);
        $book['authors'] = $this->authorService->getAuthorsByBookUuid($uuid);
        $authors = $this->authorService->findAll();

        return $this->render('update', [
            'book' => $book,
            'authors' => $authors
        ]);
    }

    /**
     * @throws Exception
     * @throws NotFoundHttpException
     * @throws ForbiddenHttpException
     */
    public function actionDelete(): string
    {
        if (!Yii::$app->user->can('manageBooks')) {
            throw new ForbiddenHttpException('Недостаточно прав для удаления книги');
        }

        if (Yii::$app->request->isPost) {
            $data = Yii::$app->request->post();
            if (!$data['uuid']) {
                throw new NotFoundHttpException('Uuid не передан');
            }

            $this->bookService->deleteBook($data['uuid']);
        }

        return $this->render('delete', [
            'books' => $this->bookService->findAllBooksAndAuthors()
        ]);
    }
}