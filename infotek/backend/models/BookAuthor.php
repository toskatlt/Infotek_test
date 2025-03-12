<?php

namespace app\models;

use yii\base\Model;

class BookAuthor extends Model
{
    public string $book_uuid;
    public string $author_uuid;

    public function rules(): array
    {
        return [
            [['book_uuid', 'author_uuid'], 'required'],
            [['book_uuid', 'author_uuid'], 'string', 'max' => 36],
        ];
    }
}