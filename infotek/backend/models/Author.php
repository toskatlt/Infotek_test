<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\db\Exception;

class Author extends Model
{
    public string $uuid;
    public string $name;

    public function rules(): array
    {
        return [
            [['uuid', 'name'], 'required'],
            [['uuid'], 'string', 'max' => 36],
            [['name'], 'string', 'max' => 255],
        ];
    }
}