<?php

namespace app\interfaces;

interface SmsServiceInterface
{
    public function sendSms(string $phone, string $message): bool;
}