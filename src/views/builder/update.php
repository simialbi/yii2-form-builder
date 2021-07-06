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
/** @var $fieldTypes array */
/** @var $relationClasses array */
/* @var $validators array */
/** @var $validatorOptions array */

FormBuilderAsset::register($this);

$this->title = Yii::t('simialbi/formbuilder', 'Update form');
$this->params['breadcrumbs'] = [
    [
        'label' => Yii::t('simialbi/formbuilder', 'My forms'),
        'url' => ['builder/index']
    ],
    $this->title
];
?>
<div class="sa-formbuilder-form-update">
    <?php $form = ActiveForm::begin([
        'id' => 'updateFormForm'
    ]); ?>

    <?= $this->render('_form', [
        'form' => $form,
        'model' => $model,
        'sections' => $sections,
        'languages' => $languages,
        'layouts' => $layouts,
        'fieldTypes' => $fieldTypes,
        'relationClasses' => $relationClasses,
        'validators' => $validators,
        'validatorOptions' => $validatorOptions
    ]); ?>

    <div class="form-row mt-4">
        <div class="col-12 form-group d-flex justify-content-end">
            <?= Html::submitButton(FAS::i('save') . ' ' . Yii::t('simialbi/formbuilder', 'Update form'), [
                'class' => ['btn', 'btn-primary']
            ]); ?>
        </div>
    </div>

    <?php ActiveForm::end(); ?>
</div>