<?php

use kartik\select2\Select2;
use rmrevin\yii\fontawesome\FAS;
use yii\helpers\Url;
use yii\widgets\Pjax;

/** @var $this \yii\web\View */
/** @var $form \yii\bootstrap4\ActiveForm */
/** @var $model \simialbi\yii2\formbuilder\models\Form */
/** @var $sections \simialbi\yii2\formbuilder\models\Section[] */
/** @var $actions \simialbi\yii2\formbuilder\models\FormAction[] */
/** @var $languages array */
/** @var $layouts array */
/** @var $fieldTypes array */
/** @var $relationClasses array */
/** @var $validators array */
/** @var $validatorOptions array */
/** @var $allActions array */
/** @var $actionOptions array */

echo $form->errorSummary($model);
?>

<div class="form-row">
    <?= $form->field($model, 'name', [
        'options' => [
            'class' => ['form-group', 'col-12', 'col-lg-5']
        ]
    ])->textInput(); ?>

    <?= $form->field($model, 'layout', [
        'options' => [
            'class' => ['form-group', 'col-8', 'col-lg-5']
        ]
    ])->widget(Select2::class, [
        'data' => $layouts,
        'theme' => Select2::THEME_KRAJEE_BS4,
        'bsVersion' => 4,
        'pluginOptions' => [
            'allowClear' => false
        ]
    ]); ?>

    <?= $form->field($model, 'language', [
        'options' => [
            'class' => ['form-group', 'col-4', 'col-lg-2']
        ]
    ])->widget(Select2::class, [
        'data' => $languages,
        'theme' => Select2::THEME_KRAJEE_BS4,
        'bsVersion' => 4,
        'pluginOptions' => [
            'allowClear' => false
        ]
    ]); ?>
</div>
<fieldset>
    <legend><?= Yii::t('simialbi/formbuilder', 'Sections'); ?></legend>
    <div id="sa-formbuilder-sections" class="accordion">
        <?php Pjax::begin([
            'id' => 'sa-formbuilder-section-pjax',
            'options' => ['class' => ['mb-3']],
            'enablePushState' => false,
            'clientOptions' => [
                'skipOuterContainers' => true
            ],
            'timeout' => 0
        ]); ?>
        <a href="<?= Url::to(['builder/add-section', 'counter' => count($sections)]); ?>"
           class="btn btn-primary btn-sm add-btn">
            <?= FAS::i('plus'); ?> <?= Yii::t('simialbi/formbuilder', 'Add section'); ?>
        </a>
        <?php Pjax::end(); ?>

        <?php for ($i = 0; $i < count($sections); $i++): ?>
            <?= $this->render('_section', [
                'form' => $form,
                'section' => $sections[$i],
                'i' => $i,
                'fieldTypes' => $fieldTypes,
                'relationClasses' => $relationClasses,
                'validators' => $validators,
                'validatorOptions' => $validatorOptions
            ]); ?>
        <?php endfor; ?>
    </div>
</fieldset>

<fieldset>
    <legend><?= Yii::t('simialbi/formbuilder', 'Actions'); ?></legend>
    <div id="sa-formbuilder-actions" class="accordion">
        <?php Pjax::begin([
            'id' => 'sa-formbuilder-action-pjax',
            'options' => ['class' => ['mb-3']],
            'enablePushState' => false,
            'clientOptions' => [
                'skipOuterContainers' => true
            ],
            'timeout' => 0
        ]); ?>
        <a href="<?= Url::to(['builder/add-action', 'counter' => count($actions)]); ?>" class="btn btn-primary btn-sm btn-add">
            <?= FAS::i('plus'); ?> <?= Yii::t('simialbi/formbuilder', 'Add action'); ?>
        </a>
        <?php Pjax::end(); ?>

        <?php for ($i = 0; $i < count($actions); $i++): ?>
            <?= $this->render('_action', [
                'form' => $form,
                'i' => $i,
                'model' => $actions[$i],
                'actions' => $allActions,
                'actionOptions' => $actionOptions,
            ]); ?>
        <?php endfor; ?>
    </div>
</fieldset>
