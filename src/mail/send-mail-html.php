<?php

use yii\bootstrap4\Html;

/** @var $this \yii\web\View */
/** @var $model \yii\base\DynamicModel */
?>

<table width="600" border="0" cellpadding="0" cellspacing="0" style="border: none; width: 600px;">
    <?php
    $i = 0;
    foreach ($model->getAttributes() as $attribute => $value):
        $bgColor = ($i % 2 === 0) ? '#ffffff' : '#f7f7f7';
        ?>
        <tr>
            <th bgcolor="<?= $bgColor; ?>" width="200" align="left" valign="top"
                style="background-color: <?= $bgColor; ?>; text-align: left; vertical-align: center; width: 200px;">
                <?= Html::encode($model->getAttributeLabel($attribute)); ?>
            </th>
            <td bgcolor="<?= $bgColor; ?>" width="400" align="left" valign="top"
                style="background-color: <?= $bgColor; ?>; text-align: left; vertical-align: center; width: 400px;">
                <?= $value; ?>
            </td>
        </tr>
        <?php
        $i++;
    endforeach;
    ?>
</table>
