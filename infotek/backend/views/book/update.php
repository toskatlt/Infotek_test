<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;

/** @var yii\web\View $this */
/** @var app\models\Book $book */
/** @var array $authors */

$this->title = 'Редактировать книгу';
$this->params['breadcrumbs'][] = ['label' => 'Книги', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $book['title'], 'url' => ['book/view', 'uuid' => $book['uuid']]];
$this->params['breadcrumbs'][] = $this->title;

$authorList = ArrayHelper::map($authors, 'uuid', 'name');
?>

<div class="book-update">
    <h1><?= Html::encode($this->title) ?></h1>

    <?php $form = ActiveForm::begin(['method' => 'post', 'action' => ['book/update', 'uuid' => $book['uuid']]]); ?>

    <?= Html::hiddenInput(Yii::$app->request->csrfParam, Yii::$app->request->csrfToken) ?>
    <?= Html::hiddenInput('uuid', $book['uuid']) ?>

    <table class="table table-bordered">
        <tr>
            <th>Название</th>
            <td><input type="text" name="title" value="<?= Html::encode($book['title']) ?>" class="form-control" required></td>
        </tr>
        <tr>
            <th>Год</th>
            <td><input type="number" name="year" value="<?= Html::encode($book['year']) ?>" class="form-control" required></td>
        </tr>
        <tr>
            <th>ISBN</th>
            <td><input type="text" name="isbn" value="<?= Html::encode($book['isbn']) ?>" class="form-control"></td>
        </tr>
        <tr>
            <th>Описание</th>
            <td><textarea name="description" class="form-control" rows="4"><?= Html::encode($book['description']) ?></textarea></td>
        </tr>
        <tr>
            <th>Авторы</th>
            <td>
                <?php foreach ($authorList as $uuid => $name): ?>
                    <div class="form-check">
                        <input type="checkbox" name="authors[]" value="<?= Html::encode($uuid) ?>"
                               class="form-check-input" id="author-<?= Html::encode($uuid) ?>"
                            <?= in_array($uuid, array_column($book['authors'], 'author_uuid')) ? 'checked' : '' ?>>
                        <label class="form-check-label" for="author-<?= Html::encode($uuid) ?>"> <?= Html::encode($name) ?> </label>
                    </div>
                <?php endforeach; ?>
            </td>
        </tr>
    </table>

    <div class="form-group">
        <button type="submit" class="btn btn-primary">Сохранить</button>
    </div>

    <?php ActiveForm::end(); ?>
</div>