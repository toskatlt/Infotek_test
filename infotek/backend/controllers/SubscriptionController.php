<?php

namespace app\controllers;

use app\service\SubscriptionService;
use yii\db\Exception;
use yii\web\ConflictHttpException;
use yii\web\ForbiddenHttpException;
use yii\web\Response;
use Yii;
use yii\web\UnprocessableEntityHttpException;

class SubscriptionController extends BaseController
{

    public function __construct($id, $module, $config = [])
    {
        parent::__construct($id, $module, $config);
    }

    /**
     * Логика подписки пользователя на автора
     *
     * @return Response
     * @throws Exception
     * @throws ConflictHttpException
     * @throws ForbiddenHttpException
     * @throws UnprocessableEntityHttpException
     */
    public function actionSubscribe(): Response
    {
        $userUuid = Yii::$app->request->post('user_uuid') ?? null;
        $authorUuid = Yii::$app->request->post('author_uuid') ?? null;

        if (!$userUuid || !$authorUuid) {
            throw new UnprocessableEntityHttpException('Необходимо указать user_uuid и author_uuid');
        }

        $service = new SubscriptionService();
        $result = $service->subscribe($userUuid, $authorUuid);

        return $this->jsonResponse([
            'success' => $result
        ]);
    }
}