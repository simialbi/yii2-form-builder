<?php

use kartik\select2\Select2;
use rmrevin\yii\fontawesome\FAS;
use simialbi\yii2\formbuilder\models\Field;
use simialbi\yii2\formbuilder\widgets\JsonEditor;
use yii\helpers\ArrayHelper;
use yii\helpers\Json;
use yii\helpers\Url;
use yii\widgets\Pjax;

/* @var $this \yii\web\View */
/* @var $form \yii\bootstrap4\ActiveForm */
/* @var $model Field */
/* @var $secI int */
/* @var $i int */
/** @var $fieldTypes array */
/** @var $relationClasses array */
/* @var $validators array */
/** @var $validatorOptions array */

?>
    <div class="card sa-formbuilder-field">
        <?= $form->field($model, "[$secI][$i]id", [
            'options' => [
                'class' => []
            ]
        ])->hiddenInput()->label(false); ?>
        <?= $form->field($model, "[$secI][$i]order", [
            'options' => [
                'class' => []
            ],
            'inputOptions' => [
                'class' => 'sortable-field'
            ]
        ])->hiddenInput()->label(false); ?>
        <div class="card-header d-flex">
            <?= $form->field($model, "[$secI][$i]label", [
                'options' => [
                    'class' => ['flex-grow-1', 'sa-formbuilder-field-label']
                ],
                'inputOptions' => [
                    'class' => ['form-control', 'form-control-sm'],
                    'placeholder' => $model->getAttributeLabel('label')
                ]
            ])->textInput()->label(false); ?>
            <?= $form->field($model, "[$secI][$i]name", [
                'options' => [
                    'class' => ['flex-grow-1', 'ml-1', 'sa-formbuilder-field-name']
                ],
                'inputOptions' => [
                    'class' => ['form-control', 'form-control-sm'],
                    'placeholder' => $model->getAttributeLabel('name')
                ]
            ])->textInput()->label(false); ?>
            <h4 class="card-title flex-grow-0 pl-3 mb-0">
                <span class="sa-formbuilder-field-sortable-handler"><?= FAS::i('grip-lines'); ?></span>
                <a href="javascript:;" class="remove-action" data-remove=".sa-formbuilder-field">
                    <?= FAS::i('trash-alt'); ?>
                </a>
                <a href="#sa-field-collapse-<?= $i; ?>" data-toggle="collapse"
                   aria-expanded="false" aria-controls="sa-field-collapse-<?= $i; ?>">
                    <?= FAS::i('angle-down'); ?>
                </a>
            </h4>
        </div>
        <div id="sa-field-collapse-<?= $i; ?>" class="collapse"
             data-parent="#sa-formbuilder-section-fields-<?= $secI; ?>">
            <div class="card-body">
                <div class="form-row">
                    <?= $form->field($model, "[$secI][$i]type", [
                        'options' => [
                            'class' => ['form-group', 'sa-formbuilder-field-type', 'col-6', 'col-lg-4']
                        ]
                    ])->widget(Select2::class, [
                        'data' => $fieldTypes,
                        'theme' => Select2::THEME_KRAJEE_BS4,
                        'bsVersion' => 4,
                        'pluginOptions' => [
                            'allowClear' => false
                        ],
                        'options' => [
                            'data' => [
                                'show' => [
                                    "#sa-formbuilder-field-$secI-$i-format"
                                ]
                            ]
                        ]
                    ]); ?>
                    <?= $form->field($model, "[$secI][$i]number_of_cols", [
                        'options' => [
                            'class' => ['form-group', 'col-6', 'col-lg-2']
                        ]
                    ])->dropdownList([
                        '' => 'auto',
                        1 => '8.33%',
                        2 => '16.66%',
                        3 => '25%',
                        4 => '33.33%',
                        5 => '41.66%',
                        6 => '50%',
                        7 => '58.33%',
                        8 => '66.66%',
                        9 => '75%',
                        10 => '83.33%',
                        11 => '91.66%',
                        12 => '100%'
                    ]); ?>
                    <?= $form->field($model, "[$secI][$i]format", [
                        'options' => [
                            'id' => "sa-formbuilder-field-$secI-$i-format",
                            'class' => ['form-group', 'col-12', 'col-lg-6'],
                            'data' => [
                                'show-condition' => [Field::TYPE_STRING]
                            ]
                        ]
                    ])->textInput(); ?>
                </div>
                <div class="form-row">
                    <?= $form->field($model, "[$secI][$i]options", [
                        'options' => [
                            'class' => ['form-group', 'sa-formbuilder-field-options', 'col-12'],
                        ]
                    ])->widget(JsonEditor::class, [
                        'clientOptions' => [
                            'modes' => ['tree']
                        ]
                    ]); ?>
                </div>
                <fieldset class="mt-3">
                    <legend><?= Yii::t('simialbi/formbuilder/field', 'Validators'); ?></legend>
                    <div class="accordion sa-formbuilder-section-field-validators"
                         id="sa-formbuilder-section-field-validator-<?= $secI; ?>-<?= $i; ?>">
                        <?php Pjax::begin([
                            'id' => "sa-formbuilder-section-$secI-field-$i-validators-pjax",
                            'options' => ['class' => ['mb-3']],
                            'enablePushState' => false,
                            'clientOptions' => [
                                'skipOuterContainers' => true
                            ],
                            'timeout' => 0
                        ]); ?>
                        <a href="<?= Url::to(['builder/add-validator', 'sectionCounter' => $secI, 'fieldCounter' => $i, 'counter' => $model->getFieldValidators()->count()]); ?>"
                           class="btn btn-primary btn-sm add-btn">
                            <?= FAS::i('plus'); ?> <?= Yii::t('simialbi/formbuilder', 'Add validator'); ?>
                        </a>
                        <?php Pjax::end(); ?>

                        <?php for ($k = 0; $k < count($model->fieldValidators); $k++): ?>
                            <?= $this->render('_validator', [
                                'fI' => $i,
                                'form' => $form,
                                'validators' => $validators,
                                'i' => $k,
                                'model' => $model->fieldValidators[$k],
                                'secI' => $secI,
                                'validatorOptions' => $validatorOptions
                            ]); ?>
                        <?php endfor; ?>
                    </div>
                </fieldset>
                <fieldset class="mt-3">
                    <legend><?= Yii::t('simialbi/formbuilder/field', 'Relation settings') ?></legend>
                    <div class="form-row">
                        <?= $form->field($model, "[$secI][$i]relation_model", [
                            'options' => [
                                'class' => ['form-group', 'sa-formbuilder-field-relation_model', 'col-12', 'col-lg-3']
                            ]
                        ])->widget(Select2::class, [
                            'data' => ArrayHelper::getColumn($relationClasses, 'name'),
                            'theme' => Select2::THEME_KRAJEE_BS4,
                            'bsVersion' => 4,
                            'options' => [
                                'placeholder' => ''
                            ],
                            'pluginOptions' => [
                                'allowClear' => true
                            ]
                        ]); ?>
                        <?= $form->field($model, "[$secI][$i]relation_field", [
                            'options' => [
                                'class' => ['form-group', 'sa-formbuilder-field-relation_field', 'col-12', 'col-lg-3']
                            ]
                        ])->widget(Select2::class, [
                            'data' => [],
                            'theme' => Select2::THEME_KRAJEE_BS4,
                            'bsVersion' => 4,
                            'options' => [
                                'placeholder' => ''
                            ],
                            'pluginOptions' => [
                                'allowClear' => true
                            ]
                        ]); ?>
                        <?= $form->field($model, "[$secI][$i]relation_display_template", [
                            'options' => [
                                'class' => ['form-group', 'col-12', 'col-lg-6']
                            ]
                        ])->textInput(); ?>
                    </div>
                </fieldset>
            </div>
        </div>
    </div>

<?php
$attributes = Json::encode(ArrayHelper::getColumn($relationClasses, 'attributes'));
$this->registerJs("window.sa.formBuilder.setAttributes($attributes);\n");
