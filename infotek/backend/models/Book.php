<?php

namespace app\models;

use app\service\AuthorService;
use yii\base\Model;
use yii\db\Exception;

class Book extends Model
{
    public string $uuid;
    public ?string $title = null;
    public ?int $year = null;
    public ?string $isbn = null;
    public ?string $description = null;

    public ?array $autors = [];

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

    /**
     * @throws Exception
     */
    public function getAuthors(): array
    {
        return (new AuthorService())->getAuthorsByBookUuid($this->getUuid());
    }
}