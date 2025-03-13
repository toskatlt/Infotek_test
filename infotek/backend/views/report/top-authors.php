<?php

use yii\helpers\Html;
use yii\web\View;
use yii\widgets\ActiveForm;
use yii\helpers\Url;

/**
 * @var View $this
 * @var array $authors // Топ-10 авторов
 * @var int $year // Выбранный год
 * @var array $yearOptions // Массив возможных годов
 */

$this->title = "Топ-10 авторов по количеству выпущенных книг в {$year} году";
$this->params['breadcrumbs'][] = ['label' => 'Отчёты', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="report-top-authors">
    <h1><?= Html::encode($this->title) ?></h1>

    <div class="year-filter">
        <?php $form = ActiveForm::begin([
            'method' => 'post',
            'action' => Url::to(['report/top-authors']),
        ]); ?>
        <div class="form-group">
            <?= Html::label('Выберите год:', 'year') ?>
            <?= Html::dropDownList('year', $year, $yearOptions, [
                'class' => 'form-control',
                'onchange' => 'this.form.submit();'
            ]) ?>
        </div>
        <?php ActiveForm::end(); ?>
    </div>

    <table class="table table-striped table-bordered">
        <thead>
        <tr>
            <th>#</th>
            <th>Автор</th>
            <th>Количество книг</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($authors as $index => $author): ?>
            <tr>
                <td><?= $index + 1 ?></td>
                <td><?= Html::encode($author['author_name']) ?></td>
                <td><?= Html::encode($author['book_count']) ?></td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
</div>