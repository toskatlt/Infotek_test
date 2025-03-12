<?php

namespace app\models;

use yii\base\Model;

class Subscription extends Model
{
    public string $user_uuid;
    public string $author_uuid;

    public function rules(): array
    {
        return [
            [['user_uuid', 'author_uuid'], 'required'],
            [['user_uuid', 'author_uuid'], 'string', 'max' => 36],
        ];
    }
}