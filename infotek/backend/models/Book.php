<?php

namespace app\models;

use app\service\AuthorService;
use yii\base\Model;
use yii\db\Exception;

class Book extends Model
{
    public string $uuid;
    public string|null $title = null;
    public int|null $year = null;
    public string|null $isbn = null;
    public string|null $description = null;
    public array $authors = [];

    public function rules(): array
    {
        return [
            [['uuid', 'title', 'year'], 'required'],
            [['uuid'], 'string', 'max' => 36],
            [['title'], 'string', 'max' => 255],
            [['year'], 'integer'],
            [['description'], 'string'],
            [['isbn'], 'string', 'max' => 20],
        ];
    }

    public function getUuid(): string
    {
        return $this->uuid;
    }

    public function getAuthors(): array
    {
        return (new AuthorService())->getAuthorsByBookUuid($this->getUuid());
    }

    public function setAuthors(array $authors): void
    {
        $this->authors = $authors;
    }
}