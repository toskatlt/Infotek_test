<?php

namespace app\controllers;

use app\service\AuthorService;
use Yii;

class ReportController extends BaseController
{
    private AuthorService $authorService;

    public function __construct(
        $id,
        $module,
        AuthorService $AuthorService,
        $config = []
    ) {
        parent::__construct($id, $module, $config);
        $this->authorService = $AuthorService;
    }

    public function actionIndex(): string
    {
        // toDo хранение записей отчетов в БД

        $reports = [
            ['title' => 'Топ-10 авторов по количеству выпущенных книг', 'url' => ['report/top-authors']],
            // Можно добавить другие отчёты сюда
        ];

        return $this->render('index', [
            'reports' => $reports
        ]);
    }

    public function actionTopAuthors()
    {
        $years = $this->authorService->getYears();

        $year = Yii::$app->request->post('year', max($years)); // Если не передан, берём максимальный год
        $authors = $this->authorService->getTopAuthorsByYear($year);

        return $this->render('top-authors', [
            'authors' => $authors,
            'yearOptions' => array_combine($years, $years),
            'year' => $year,
        ]);
    }
}