<?php

use yii\helpers\Html;
use yii\web\View;

/**
 * @var View $this
 * @var array $reports // Массив отчётов
 */

$this->title = 'Отчёты';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="report-index">
    <h1><?= Html::encode($this->title) ?></h1>

    <ul class="list-group">
        <?php foreach ($reports as $index => $report): ?>
            <li class="list-group-item">
                <strong><?= Html::encode($index + 1) ?>.</strong>
                <?= Html::a(Html::encode($report['title']), $report['url'], ['class' => 'btn btn-primary']) ?>
            </li>
        <?php endforeach; ?>
    </ul>
</div>
