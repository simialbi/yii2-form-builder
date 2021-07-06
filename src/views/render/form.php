<?php

use yii\bootstrap4\ActiveForm;
use yii\bootstrap4\Html;

/** @var $this \yii\web\View */
/** @var $model \yii\base\DynamicModel */
/** @var $sections \simialbi\yii2\formbuilder\models\Section[] */
/** @var $name string */
/** @var $layout string */

$this->title = $name;
$this->params['breadcrumbs'] = [$this->title];
?>

<div class="sa-formbuilder-render-form">
    <?php $form = ActiveForm::begin(); ?>
        <?= $this->render("_$layout", [
            'model' => $model,
            'form' => $form,
            'sections' => $sections
        ]); ?>
        <div class="form-group mt-3 text-right">
            <?= Html::submitButton(Yii::t('simialbi/formbuilder', 'Save'), [
                'class' => ['btn', 'btn-success']
            ]); ?>
        </div>
    <?php ActiveForm::end(); ?>
</div>
