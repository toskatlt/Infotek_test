<?php

namespace app\controllers;

use app\service\AuthorService;
use app\service\SubscriptionService;
use Yii;
use yii\db\Exception;
use yii\web\Response;

class AuthorController extends BaseController
{
    private AuthorService $authorService;
    private SubscriptionService $subscriptionService;

    public function __construct(
        $id,
        $module,
        AuthorService $AuthorService,
        SubscriptionService $SubscriptionService,
        $config = []
    )
    {
        parent::__construct($id, $module, $config);
        $this->authorService = $AuthorService;
        $this->subscriptionService = $SubscriptionService;
    }

    /**
     * @throws Exception
     */
    public function actionIndex(): string
    {
        if (!Yii::$app->user->isGuest) {
            $userUuid = Yii::$app->user->identity->getUuid();
            $subscribedAuthors = $this->subscriptionService->findSubscribedAuthors($userUuid);
            $subscribedAuthors = array_column($subscribedAuthors, 'author_uuid');
        } else {
            $subscribedAuthors = [];
        }

        return $this->render('index', [
            'authors' => $this->authorService->findAll(),
            'subscribedAuthors' => $subscribedAuthors,
        ]);
    }

    /**
     * @throws Exception
     */
    public function actionView(string $uuid): Response
    {
        $author = $this->AuthorService->findOne($uuid);

        if (!$author) {
            throw new Exception('Автор не найден');
        }

        return $this->jsonResponse($author);
    }
}