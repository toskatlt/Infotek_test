<?php

namespace app\service;

use app\Interfaces\SmsServiceInterface;
use Yii;

class SmsPilotService implements SmsServiceInterface
{
    private string $apiKey;
    private string $sender;

    public function __construct(string $apiKey, string $sender)
    {
        $this->apiKey = $apiKey;
        $this->sender = $sender;
    }

    public function sendSms(string $phone, string $message): bool
    {
        $url = 'https://smspilot.ru/api.php'
            .'?send=' . urlencode($message)
            .'&to=' . urlencode($phone)
            .'&from=' . urlencode($this->sender)
            .'&apikey=' . $this->apiKey
            .'&format=json';

        $response = file_get_contents($url);
        $data = json_decode($response, true);

        if (!isset($data['error'])) {
            return true;
        }

        Yii::warning('Ошибка отправки SMS: ' . $data['error']['description_ru'], __METHOD__);

        return false;
    }
}