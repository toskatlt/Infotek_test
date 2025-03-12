<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\View;

/**
 * @var View $this
 * @var array $authors // Передаваемый массив авторов
 * @var array $subscribedAuthors // Список авторов, на которых подписан пользователь
 */

$this->title = 'Авторы';
$this->params['breadcrumbs'][] = $this->title;

// Подключение jQuery и Yii JS
$this->registerJsFile('@web/js/jquery.min.js', ['depends' => [\yii\web\JqueryAsset::class]]);
$this->registerJsFile('@web/js/yii.js', ['depends' => [\yii\web\YiiAsset::class]]);
?>

<table class="table table-hover table-responsive dataTable">
    <thead>
    <tr>
        <th>№</th>
        <th>ФИО автора</th>
        <th>Подписка</th>
    </tr>
    </thead>
    <tbody>
    <?php foreach ($authors as $index => $author): ?>
        <tr>
            <td><?= Html::encode($index + 1) ?></td>
            <td><?= Html::encode($author['name']) ?></td>
            <td>
                <?php if (in_array($author['uuid'], $subscribedAuthors)): ?>
                    <button class="btn btn-success" disabled>Subscribed</button>
                <?php else: ?>
                    <?= Html::button(
                        '<span class="glyphicon glyphicon-bell"></span> Subscribe',
                        [
                            'class' => 'subscribe-btn btn btn-primary',
                            'data-user_uuid' => Yii::$app->user->identity->uuid ?? 'guest',
                            'data-author_uuid' => $author['uuid'],
                            'title' => 'Subscribe to this author'
                        ]
                    ) ?>
                <?php endif; ?>
            </td>
        </tr>
    <?php endforeach; ?>
    </tbody>
</table>

<?php
$url = Url::to(['/subscription/subscribe']);
$script = <<< JS
    $(document).ready(function() {
        $(document).on('click', '.subscribe-btn', function (e) {
            e.preventDefault();

            let btn = $(this);
            let userUuid = btn.data('user_uuid');
            let authorUuid = btn.data('author_uuid');

            let csrfToken = (typeof yii !== 'undefined' && yii.getCsrfToken) ? yii.getCsrfToken() : $('meta[name="csrf-token"]').attr('content');
            
            $.ajax({
                url: "$url",
                type: 'POST',
                data: {
                    user_uuid: userUuid,
                    author_uuid: authorUuid,
                    _csrf: csrfToken
                },
                dataType: 'json', // Явно указываем, что ожидаем JSON
                success: function (response) {
                    console.log("Ответ от сервера:", response);
                    if (response.success) {
                        alert('Подписка оформлена!');
                        btn.html('<span class="glyphicon glyphicon-ok"></span> Subscribed');
                        btn.removeClass('subscribe-btn btn-primary').addClass('btn-success').prop('disabled', true);
                    } else {
                        alert('Ошибка: ' + response.message);
                    }
                },
                error: function (xhr, status, error) {
                    console.error("Ошибка AJAX запроса:", xhr.responseText);
                    alert('Ошибка при подписке. Проверьте консоль для деталей.');
                }
            });
        });
    });
JS;
$this->registerJs($script);
?>
