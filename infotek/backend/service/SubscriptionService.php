<?php

namespace app\service;

use app\models\Subscription;
use app\repositories\SubscriptionRepository;
use Yii;
use yii\db\Exception;
use yii\web\ConflictHttpException;
use yii\web\ForbiddenHttpException;
use yii\web\UnprocessableEntityHttpException;

class SubscriptionService
{

    /**
     * Подписка пользователя на автора
     *
     * @param string $userUuid - uuid пользователя
     * @param string $authorUuid - uuid автора
     * @throws UnprocessableEntityHttpException
     * @throws ForbiddenHttpException
     * @throws ConflictHttpException
     * @throws Exception
     *
     * @return bool
     */
    public function subscribe(string $userUuid, string $authorUuid): bool
    {
        $subscription = new Subscription([
            'user_uuid' => $userUuid,
            'author_uuid' => $authorUuid,
        ]);

        if (!$subscription->validate()) {
            throw new UnprocessableEntityHttpException(json_encode($subscription->errors));
        }

        if (!Yii::$app->user->can('subscribeAuthors')) {
            throw new ForbiddenHttpException('У вас нет прав для подписки на этого автора');
        }

        if (SubscriptionRepository::exists($userUuid, $authorUuid)) {
            throw new ConflictHttpException ('Вы уже подписаны на этого автора');
        }

        return SubscriptionRepository::save($subscription);
    }

    /**
     * @throws Exception
     */
    public function findSubscribedAuthors(string $userUuid): array
    {
        return SubscriptionRepository::findAllAuthorsByUserUuid($userUuid);
    }
}