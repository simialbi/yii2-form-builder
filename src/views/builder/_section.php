<?php

use rmrevin\yii\fontawesome\FAS;
use yii\helpers\Url;
use yii\widgets\Pjax;

/** @var $this \yii\web\View */
/** @var $form \yii\bootstrap4\ActiveForm */
/** @var $section \simialbi\yii2\formbuilder\models\Section */
/** @var $i integer */

?>
<div class="card sa-formbuilder-section">
    <?= $form->field($section, "[$i]id", [
        'options' => [
            'class' => []
        ]
    ])->hiddenInput()->label(false); ?>
    <?= $form->field($section, "[$i]order", [
        'options' => [
            'class' => []
        ],
        'inputOptions' => [
            'class' => 'sortable-field'
        ]
    ])->hiddenInput()->label(false); ?>
    <div class="card-header d-flex">
        <?= $form->field($section, "[$i]name", [
            'options' => [
                'class' => ['flex-grow-1']
            ],
            'inputOptions' => [
                'class' => ['form-control', 'form-control-sm'],
                'placeholder' => $section->getAttributeLabel('name')
            ]
        ])->textInput()->label(false); ?>
        <h4 class="card-title flex-grow-0 pl-3 mb-0">
            <span class="sa-formbuilder-section-sortable-handler"><?= FAS::i('grip-lines'); ?></span>
            <a href="javascript:;" class="remove-action" data-remove=".sa-formbuilder-section">
                <?= FAS::i('trash-alt'); ?>
            </a>
            <a href="#sa-section-collapse-<?= $i; ?>" data-toggle="collapse"
               aria-expanded="<?= $i === 0 ? 'true' : 'false'; ?>" aria-controls="sa-section-collapse-<?= $i; ?>">
                <?= FAS::i('angle-down'); ?>
            </a>
        </h4>
    </div>
    <div id="sa-section-collapse-<?= $i; ?>" class="collapse<?= $i === 0 ? ' show' : ''; ?>"
         data-parent="#sa-formbuilder-sections">
        <div class="card-body">
            <?= $form->field($section, "[$i]default_number_of_cols", [
                'options' => [
                    'class' => ['form-group']
                ]
            ])->textInput(['type' => 'number', 'min' => 2, 'max' => 6]); ?>

            <fieldset>
                <legend><?= Yii::t('simialbi/formbuilder/section', 'Section fields'); ?></legend>
                <div class="accordion sa-formbuilder-section-fields" id="sa-formbuilder-section-fields-<?= $i; ?>">
                    <?php Pjax::begin([
                        'id' => "sa-formbuilder-section-$i-field-pjax",
                        'options' => ['class' => ['mb-3']],
                        'enablePushState' => false,
                        'clientOptions' => [
                            'skipOuterContainers' => true
                        ],
                        'timeout' => 0
                    ]); ?>
                        <a href="<?= Url::to(['builder/add-field', 'sectionCounter' => $i, 'counter' => 0]); ?>" class="btn btn-primary btn-sm">
                            <?= FAS::i('plus'); ?> <?= Yii::t('simialbi/formbuilder', 'Add field'); ?>
                        </a>
                    <?php Pjax::end(); ?>
                </div>
            </fieldset>
        </div>
    </div>
</div>
