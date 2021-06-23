<?php

use rmrevin\yii\fontawesome\FAS;
use yii\bootstrap4\ActiveForm;
use yii\helpers\Json;
use yii\helpers\Url;
use yii\widgets\Pjax;

/** @var $this \yii\web\View */
/** @var $model \simialbi\yii2\formbuilder\models\Validator */
/** @var $sectionCounter integer */
/** @var $fieldCounter integer */
/** @var $counter integer */
/** @var $validators array */
/** @var $validatorOptions array */

$form = ActiveForm::begin(['id' => 'createFormForm']);

Pjax::begin([
    'id' => "sa-formbuilder-section-$sectionCounter-field-$fieldCounter-validators-pjax",
    'options' => ['class' => ['mb-3']],
    'enablePushState' => false,
    'clientOptions' => [
        'skipOuterContainers' => true
    ],
    'timeout' => 0
]); ?>
    <a href="<?= Url::to(['builder/add-validator', 'sectionCounter' => $sectionCounter, 'fieldCounter' => $fieldCounter, 'counter' => $counter + 1]); ?>"
       class="btn btn-primary btn-sm">
        <?= FAS::i('plus'); ?> <?= Yii::t('simialbi/formbuilder', 'Add validator'); ?>
    </a>
<?php

echo $this->render('_validator', [
    'form' => $form,
    'model' => $model,
    'secI' => $sectionCounter,
    'fI' => $fieldCounter,
    'i' => $counter,
    'validators' => $validators,
    'validatorOptions' => $validatorOptions
]);

$js = '';
foreach ($form->attributes as $attribute) {
    $json = Json::encode($attribute);
    $js .= "jQuery('#{$form->id}').yiiActiveForm('add', $json);\n";
}
$this->registerJs($js);

Pjax::end();

ActiveForm::end();
