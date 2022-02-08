<?php

use yii\console\widgets\Table;

/** @var $this \yii\web\View */
/** @var $model \yii\base\DynamicModel */

$rows = [];
foreach ($model->getAttributes() as $attribute => $value) {
    $rows[] = [$model->getAttributeLabel($attribute), $value];
}

echo Table::widget([
    'rows' => $rows,
    'screenWidth' => 600
]);
