<?php

use rmrevin\yii\fontawesome\FAS;
use yii\bootstrap4\ActiveForm;
use yii\helpers\Json;
use yii\helpers\Url;
use yii\widgets\Pjax;

/** @var $this \yii\web\View */
/** @var $model \simialbi\yii2\formbuilder\models\Section */
/** @var $counter integer */

$form = ActiveForm::begin(['id' => 'createFormForm']);

Pjax::begin([
    'id' => 'sa-formbuilder-section-pjax',
    'options' => ['class' => ['mb-3']],
    'enablePushState' => false,
    'clientOptions' => [
        'skipOuterContainers' => true
    ],
    'timeout' => 0
]);
?>
    <a href="<?= Url::to(['builder/add-section', 'counter' => $counter + 1]); ?>" class="btn btn-primary btn-sm">
        <?= FAS::i('plus'); ?> <?= Yii::t('simialbi/formbuilder', 'Add section'); ?>
    </a>
<?php

echo $this->render('_section', [
    'form' => $form,
    'section' => $model,
    'i' => $counter
]);

$js = '';
foreach ($form->attributes as $attribute) {
    $json = Json::encode($attribute);
    $js .= "jQuery('#{$form->id}').yiiActiveForm('add', $json);\n";
}
$this->registerJs($js);

Pjax::end();

ActiveForm::end();
