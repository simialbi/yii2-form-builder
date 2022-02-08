<?php

use kartik\select2\Select2;
use rmrevin\yii\fontawesome\FAS;
use yii\bootstrap4\Html;
use yii\helpers\Json;

/** @var $this \yii\web\View */
/** @var $form \yii\bootstrap4\ActiveForm */
/** @var $model \simialbi\yii2\formbuilder\models\FormAction */
/** @var $i integer */
/** @var $actions array */
/** @var $actionOptions array */

?>
    <div class="card sa-formbuilder-action">
        <div class="card-header d-flex">
            <?= Yii::t('simialbi/formbuilder/action', 'Action {action}', [
                'action' => $model->isNewRecord ? '' : $model->action->translatedName
            ]); ?>
            <h4 class="card-title flex-grow-0 pl-3 mb-0">
                <a href="javascript:;" class="remove-action" data-remove=".sa-formbuilder-action">
                    <?= FAS::i('trash-alt'); ?>
                </a>
                <a href="#sa-formbuilder-action-collapse-<?= $i; ?>" data-toggle="collapse"
                   aria-expanded="false" aria-controls="sa-formbuilder-action-collapse-<?= $i; ?>">
                    <?= FAS::i('angle-down'); ?>
                </a>
            </h4>
        </div>
        <div id="sa-formbuilder-action-collapse-<?= $i; ?>" class="collapse">
            <div class="card-body">
                <div class="form-row">
                    <?= $form->field($model, "[$i]action_id", [
                        'options' => [
                            'class' => ['form-group', 'sa-formbuilder-action-id', 'col-6', 'col-lg-4']
                        ]
                    ])->widget(Select2::class, [
                        'data' => $actions,
                        'theme' => Select2::THEME_KRAJEE_BS4,
                        'bsVersion' => 4,
                        'pluginOptions' => [
                            'allowClear' => false
                        ],
                        'options' => [
                            'data' => [
                                'container' => "#sa-formbuilder-action-form-$i"
                            ]
                        ]
                    ]); ?>
                </div>
                <div class="form-row">
                    <div class="col-12">
                        <div id="sa-formbuilder-action-form-<?= $i; ?>"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php
$acts = Json::encode($actionOptions);
$this->registerJs("window.sa.formBuilder.setActions($acts);");

if ($model->configuration) {
    $this->registerJs("window.sa.formBuilder.initAction.apply(jQuery('#" . Html::getInputId($model, "[$i]action_id") . "').get(0), [{$model->configuration}]);");
}
