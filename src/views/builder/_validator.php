<?php

use kartik\select2\Select2;
use rmrevin\yii\fontawesome\FAS;

/* @var $this \yii\web\View */
/* @var $form \yii\base\Widget|\yii\bootstrap4\ActiveForm */
/* @var $model \simialbi\yii2\formbuilder\models\Validator */
/* @var $secI int */
/* @var $fI int */
/* @var $i int */
/* @var $validators array */
/** @var $validatorOptions array */

?>
<div class="card sa-formbuilder-validator">
    <?= $form->field($model, "[$secI][$fI][$i]id", [
        'options' => [
            'class' => []
        ]
    ])->hiddenInput()->label(false); ?>
    <div class="card-header d-flex">
        <?= $form->field($model, "[$secI][$fI][$i]name", [
            'options' => [
                'class' => ['flex-grow-1', 'ml-1', 'sa-formbuilder-field-name']
            ],
            'inputOptions' => [
                'class' => ['form-control', 'form-control-sm'],
                'placeholder' => $model->getAttributeLabel('name')
            ]
        ])->textInput()->label(false); ?>
        <h4 class="card-title flex-grow-0 pl-3 mb-0">
            <a href="javascript:;" class="remove-action" data-remove=".sa-formbuilder-validator">
                <?= FAS::i('trash-alt'); ?>
            </a>
            <a href="#sa-validator-collapse-<?= $secI; ?>-<?= $fI; ?>-<?= $i; ?>" data-toggle="collapse"
               aria-expanded="false" aria-controls="sa-validator-collapse-<?= $secI; ?>-<?= $fI; ?>-<?= $i; ?>">
                <?= FAS::i('angle-down'); ?>
            </a>
        </h4>
    </div>
    <div id="sa-validator-collapse-<?= $secI; ?>-<?= $fI; ?>-<?= $i; ?>" class="collapse"
         data-parent="#sa-formbuilder-section-field-validator-<?= $secI; ?>-<?= $fI; ?>">
        <div class="card-body">
            <div class="form-row">
                <?= $form->field($model, "[$secI][$fI][$i]class", [
                    'options' => [
                        'class' => ['form-group', 'col-6', 'col-lg-4']
                    ]
                ])->widget(Select2::class, [
                    'data' => $validators,
                    'theme' => Select2::THEME_KRAJEE_BS4,
                    'bsVersion' => 4,
                    'pluginOptions' => [
                        'allowClear' => false
                    ]
                ]); ?>
            </div>
        </div>
    </div>
</div>
