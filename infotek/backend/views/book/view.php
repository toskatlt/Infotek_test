<?php

use yii\helpers\Html;
use yii\web\View;
use yii\helpers\Url;

/**
 * @var View $this
 * @var array $book // Данные о книге
 */

$this->title = $book['title'];
$this->params['breadcrumbs'][] = ['label' => 'Книги', 'url' => ['book/index']];
$this->params['breadcrumbs'][] = $this->title;
$this->params['breadcrumbs'][] = ['label' => 'Добавить книгу', 'url' => ['book/create']];
$this->params['breadcrumbs'][] = ['label' => 'Удалить книгу', 'url' => ['book/delete']];

$this->registerCss(".book-cover { width: 150px; height: auto; display: block; }");
?>

<div class="book-view">
    <h1><?= Html::encode($book['title']) ?></h1>
    <p><strong>Авторы:</strong> <?= Html::encode($book['authors_name']) ?></p>
    <p><strong>Год выпуска:</strong> <?= Html::encode($book['year'] ?? 'Не указан') ?></p>
    <p><strong>ISBN:</strong> <?= Html::encode($book['isbn'] ?? 'Нет данных') ?></p>
    <p><strong>Описание:</strong> <?= Html::encode($book['description'] ?? 'Нет описания') ?></p>
    <img src="<?= Url::to("@web/images/" . Html::encode($book['uuid']) . ".webp") ?>" class="book-cover" alt="Обложка книги" onerror="this.onerror=null;this.src='<?= Url::to("@web/web/images/default.webp") ?>';">

    <div class="form-group mt-3">
        <?= Html::a('Редактировать', ['book/update', 'uuid' => $book['uuid']], ['class' => 'btn btn-primary']) ?>
    </div>
</div>
