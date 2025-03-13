<?php

use yii\helpers\Html;
use yii\widgets\Pjax;
use yii\helpers\Url;

/**
 * @var array $books // Передаваемый массив книг
 */

$this->title = 'Удаление книг';
$this->params['breadcrumbs'][] = ['label' => 'Книги', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="book-index">
    <h1><?= Html::encode($this->title) ?></h1>

    <?php Pjax::begin(); ?>
    <table class="table table-striped table-bordered">
        <thead>
        <tr>
            <th>#</th>
            <th>Название</th>
            <th>Год</th>
            <th>Авторы</th>
            <th>Обложка</th>
            <th></th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($books as $index => $book): ?>
            <tr>
                <td><?= $index + 1 ?></td>
                <td><?= Html::encode($book['title']) ?></td>
                <td><?= Html::encode($book['year']) ?></td>
                <td><?= Html::encode($book['authors_name']) ?></td>
                <td><?= Url::to("@web/images/{$book['uuid']}.webp") ?></td>
                <td>
                    <?= Html::beginForm(['delete'], 'post'); ?>
                    <?= Html::hiddenInput('uuid', $book['uuid']); ?>
                    <?= Html::submitButton(
                        '<i class="fas fa-trash-alt"></i> Удалить',
                        [
                            'class' => 'btn btn-danger btn-sm',
                            'data' => [
                                'confirm' => 'Вы уверены, что хотите удалить эту книгу?',
                            ],
                        ]
                    ); ?>
                    <?= Html::endForm(); ?>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
    <?php Pjax::end(); ?>
</div>