<?php

namespace app\service;

use app\Interfaces\SmsServiceInterface;
use Yii;

class SmsService
{
    private SmsServiceInterface $smsProvider;

    public function __construct()
    {
        $config = Yii::$app->params['smsProvider'] ?? [];

        $provider = $config['provider'] ?? '';
        $apiKey = $config['APIKey'] ?? '';
        $sender = $config['sender'] ?? 'test';

        $this->smsProvider = match (strtolower($provider)) {
            'pilot' => new SmsPilotService($apiKey, $sender),
            default => $this->handleUnknownProvider($provider),
        };
    }

    private function handleUnknownProvider(string $provider)
    {
        // toDo логирование ошибка в названии провайдера или его отсуствие

        return null;
    }

    /**
     * Отправка SMS
     *
     * @param string $phone
     * @param string $message
     * @return bool
     */
    public function sendSms(string $phone, string $message): bool
    {
        return $this->smsProvider->sendSms($phone, $message);
    }
}