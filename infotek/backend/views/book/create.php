<?php

use yii\helpers\Html;
use yii\helpers\ArrayHelper;

/** @var yii\web\View $this */
/** @var array $authors */

$this->title = 'Добавить книгу';
$this->params['breadcrumbs'][] = ['label' => 'Книги', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

$authorList = ArrayHelper::map($authors, 'uuid', 'name');
?>

<div class="book-create">
    <h1><?= Html::encode($this->title) ?></h1>

    <form method="post" action="<?= Html::encode(Yii::$app->urlManager->createUrl(['book/create'])) ?>">
        <?= Html::hiddenInput(Yii::$app->request->csrfParam, Yii::$app->request->csrfToken) ?>

        <div class="form-group">
            <label for="title">Название</label>
            <input type="text" name="title" id="title" class="form-control" required>
        </div>

        <div class="form-group">
            <label for="year">Год</label>
            <input type="number" name="year" id="year" class="form-control" required>
        </div>

        <div class="form-group">
            <label for="isbn">ISBN</label>
            <input type="text" name="isbn" id="isbn" class="form-control">
        </div>

        <div class="form-group">
            <label for="description">Описание</label>
            <textarea name="description" id="description" class="form-control" rows="4"></textarea>
        </div>

        <div class="form-group">
            <label>Авторы</label>
            <?php foreach ($authorList as $uuid => $name): ?>
                <div class="form-check">
                    <input type="checkbox" name="authors[]" value="<?= Html::encode($uuid) ?>" class="form-check-input" id="author-<?= Html::encode($uuid) ?>">
                    <label class="form-check-label" for="author-<?= Html::encode($uuid) ?>"> <?= Html::encode($name) ?> </label>
                </div>
            <?php endforeach; ?>
        </div>

        <div class="form-group">
            <button type="submit" class="btn btn-success">Сохранить</button>
        </div>
    </form>
</div>