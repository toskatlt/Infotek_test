<?php

namespace app\widgets\grid;

use Yii;
use yii\grid\ActionColumn as BaseActionColumn;

class ActionColumn extends BaseActionColumn
{
    /**
     * {@inheritdoc}
     */
    protected function renderDataCellContent($model, $key, $index)
    {
        if (Yii::$app->user->isGuest) {
            return $this->renderDataCellContentAsGuest($model, $key, $index);
        }

        return parent::renderDataCellContent($model, $key, $index);
    }

    /**
     * Отображение кнопок для гостей
     *
     * @param mixed $model
     * @param mixed $key
     * @param int $index
     *
     * @return string
     */
    protected function renderDataCellContentAsGuest($model, $key, $index)
    {
        $this->template = '{view}';

        return parent::renderDataCellContent($model, $key, $index);
    }
}