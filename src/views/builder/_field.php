<?php

use kartik\select2\Select2;
use rmrevin\yii\fontawesome\FAS;
use simialbi\yii2\formbuilder\models\Field;
use yii\helpers\ArrayHelper;

/* @var $this \yii\web\View */
/* @var $form \yii\bootstrap4\ActiveForm */
/* @var $model Field */
/* @var $secI int */
/* @var $i int */
/** @var $fieldTypes array */
/** @var $dependencyOperators array */
/** @var $relationClasses array */


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
    <div id="sa-field-collapse-<?= $i; ?>" class="collapse" data-parent="#sa-formbuilder-section-fields-<?= $secI; ?>">
        <div class="card-body">
            <div class="form-row">
                <?= $form->field($model, "[$secI][$i]type", [
                    'options' => [
                        'class' => ['form-group', 'col-12', 'col-lg-4']
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
                                "#sa-formbuilder-field-$secI-$i-min",
                                "#sa-formbuilder-field-$secI-$i-max",
                                "#sa-formbuilder-field-$secI-$i-multiple"
                            ]
                        ]
                    ]
                ]); ?>
                <?= $form->field($model, "[$secI][$i]defaultValue", [
                    'options' => [
                        'class' => ['form-group', 'col-12', 'col-lg-4']
                    ]
                ])->textInput(); ?>
                <?= $form->field($model, "[$secI][$i]min", [
                    'options' => [
                        'id' => "sa-formbuilder-field-$secI-$i-min",
                        'class' => ['form-group', 'col-6', 'col-lg-2'],
                        'data' => [
                            'show-condition' => [Field::TYPE_INT, Field::TYPE_DOUBLE]
                        ]
                    ]
                ])->textInput(); ?>
                <?= $form->field($model, "[$secI][$i]max", [
                    'options' => [
                        'id' => "sa-formbuilder-field-$secI-$i-max",
                        'class' => ['form-group', 'col-6', 'col-lg-2'],
                        'data' => [
                            'show-condition' => [Field::TYPE_INT, Field::TYPE_DOUBLE]
                        ]
                    ]
                ])->textInput(); ?>
            </div>
            <div class="form-row">
                <div class="col-12 d-flex">
                    <?= $form->field($model, "[$secI][$i]required", [
                        'options' => [
                            'class' => ['form-group']
                        ]
                    ])->checkbox(); ?>
                    <?= $form->field($model, "[$secI][$i]multiple", [
                        'options' => [
                            'id' => "sa-formbuilder-field-$secI-$i-multiple",
                            'class' => ['form-group', 'ml-3'],
                            'data' => [
                                'show-condition' => [Field::TYPE_FILE, Field::TYPE_SELECT]
                            ]
                        ]
                    ])->checkbox()->inline(true); ?>
                </div>
            </div>
            <fieldset class="mt-3">
                <legend><?= Yii::t('simialbi/formbuilder/field', 'Dependency settings'); ?></legend>
                <div class="form-row">
                    <?= $form->field($model, "[$secI][$i]dependency_operator", [
                        'options' => [
                            'class' => ['form-group', 'col-6', 'col-lg-2']
                        ]
                    ])->widget(Select2::class, [
                        'data' => $dependencyOperators,
                        'theme' => Select2::THEME_KRAJEE_BS4,
                        'bsVersion' => 4,
                        'options' => [
                            'placeholder' => ''
                        ],
                        'pluginOptions' => [
                            'allowClear' => true
                        ]
                    ]); ?>
                    <?= $form->field($model, "[$secI][$i]dependency_id", [
                        'options' => [
                            'class' => ['form-group', 'col-6', 'col-lg-3']
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
                </div>
            </fieldset>
            <fieldset class="mt-3">
                <legend><?= Yii::t('simialbi/formbuilder/field', 'Relation settings') ?></legend>
                <div class="form-row">
                    <?= $form->field($model, "[$secI][$i]relation_model", [
                        'options' => [
                            'class' => ['form-group', 'col-12', 'col-lg-3']
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
                            'class' => ['form-group', 'col-12', 'col-lg-3']
                        ]
                    ])->widget(Select2::class, [
                        'data' => ArrayHelper::getColumn($relationClasses, 'attributes'),
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
