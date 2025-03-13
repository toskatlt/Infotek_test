<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\View;

/**
 * @var View $this
 * @var array $books // Передаваемый массив книг
 */

$this->title = 'Книги';
$this->params['breadcrumbs'][] = $this->title;
$this->params['breadcrumbs'][] = ['label' => 'Добавить книгу', 'url' => ['book/create']];
$this->params['breadcrumbs'][] = ['label' => 'Удалить книгу', 'url' => ['book/delete']];

$this->registerCss(".book-cover { width: 50px; height: auto; }");
?>

<table class="table table-hover table-responsive dataTable">
    <thead>
    <tr>
        <th>№</th>
        <th>Название книги</th>
        <th>Авторы</th>
        <th>Обложка</th>
    </tr>
    </thead>
    <tbody>
    <?php foreach ($books as $index => $book): ?>
        <tr>
            <td><?= Html::encode($index + 1) ?></td>
            <td><?= Html::a(Html::encode($book['title']), ['book/view', 'uuid' => $book['uuid']], ['class' => 'book-link']) ?>
            <td><?= Html::encode($book['authors_name']) ?></td>
            <td>
                <img src="<?= Url::to("@web/images/{$book['uuid']}.webp") ?>" class="book-cover" alt="Обложка книги">
            </td>
        </tr>
    <?php endforeach; ?>
    </tbody>
</table>