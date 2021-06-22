<?php

use rmrevin\yii\fontawesome\FAS;
use simialbi\yii2\formbuilder\FormBuilderAsset;
use yii\bootstrap4\ActiveForm;
use yii\bootstrap4\Html;

/** @var $this \yii\web\View */
/** @var $model \simialbi\yii2\formbuilder\models\Form */
/** @var $sections \simialbi\yii2\formbuilder\models\Section[] */
/** @var $languages array */
/** @var $layouts array */

FormBuilderAsset::register($this);

$this->title = Yii::t('simialbi/formbuilder', 'Create form');
$this->params['breadcrumbs'] = [
    [
        'label' => Yii::t('simialbi/formbuilder', 'My forms'),
        'url' => ['ticket/index']
    ],
    $this->title
];
?>
<div class="sa-formbuilder-form-create">
    <?php $form = ActiveForm::begin([
        'id' => 'createFormForm'
    ]); ?>

    <?= $this->render('_form', [
        'form' => $form,
        'model' => $model,
        'sections' => $sections,
        'languages' => $languages,
        'layouts' => $layouts
    ]); ?>

    <div class="form-row mt-4">
        <div class="col-12 form-group d-flex justify-content-end">
            <?= Html::submitButton(FAS::i('save') . ' ' . Yii::t('simialbi/formbuilder', 'Create form'), [
                'class' => ['btn', 'btn-primary']
            ]); ?>
        </div>
    </div>

    <?php ActiveForm::end(); ?>
</div>
