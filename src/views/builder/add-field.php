<?php

use rmrevin\yii\fontawesome\FAS;
use yii\bootstrap4\ActiveForm;
use yii\helpers\Json;
use yii\helpers\Url;
use yii\widgets\Pjax;

/** @var $this \yii\web\View */
/** @var $model \simialbi\yii2\formbuilder\models\Field */
/** @var $sectionCounter integer */
/** @var $counter integer */
/** @var $fieldTypes array */
/** @var $dependencyOperators array */
/** @var $relationClasses array */

$form = ActiveForm::begin(['id' => 'createFormForm']);

Pjax::begin([
    'id' => "sa-formbuilder-section-$sectionCounter-field-pjax",
    'options' => ['class' => ['mb-3']],
    'enablePushState' => false,
    'clientOptions' => [
        'skipOuterContainers' => true
    ],
    'timeout' => 0
]); ?>
    <a href="<?= Url::to(['builder/add-field', 'sectionCounter' => $sectionCounter, 'counter' => $counter + 1]); ?>"
       class="btn btn-primary btn-sm">
        <?= FAS::i('plus'); ?> <?= Yii::t('simialbi/formbuilder', 'Add field'); ?>
    </a>
<?php

echo $this->render('_field', [
    'form' => $form,
    'model' => $model,
    'secI' => $sectionCounter,
    'i' => $counter,
    'fieldTypes' => $fieldTypes,
    'dependencyOperators' => $dependencyOperators,
    'relationClasses' => $relationClasses
]);

$js = '';
foreach ($form->attributes as $attribute) {
    $json = Json::encode($attribute);
    $js .= "jQuery('#{$form->id}').yiiActiveForm('add', $json);\n";
}
$this->registerJs($js);

Pjax::end();

ActiveForm::end();
