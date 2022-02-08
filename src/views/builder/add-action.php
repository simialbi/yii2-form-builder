<?php

use rmrevin\yii\fontawesome\FAS;
use yii\bootstrap4\ActiveForm;
use yii\helpers\Json;
use yii\helpers\Url;
use yii\widgets\Pjax;

/** @var $this \yii\web\View */
/** @var $model \simialbi\yii2\formbuilder\models\Action */
/** @var $counter integer */
/** @var $actions array */
/** @var $actionOptions array */

$form = ActiveForm::begin(['id' => 'buildFormForm']);

Pjax::begin([
    'id' => 'sa-formbuilder-action-pjax',
    'options' => ['class' => ['mb-3']],
    'enablePushState' => false,
    'clientOptions' => [
        'skipOuterContainers' => true
    ],
    'timeout' => 0
]); ?>
    <a href="<?= Url::to(['builder/add-action', 'counter' => $counter + 1]); ?>"
       class="btn btn-primary btn-sm add-btn">
        <?= FAS::i('plus'); ?> <?= Yii::t('simialbi/formbuilder', 'Add action'); ?>
    </a>
<?php

echo $this->render('_action', [
    'form' => $form,
    'model' => $model,
    'i' => $counter,
    'actions' => $actions,
    'actionOptions' => $actionOptions
]);

$js = '';
foreach ($form->attributes as $attribute) {
    $json = Json::encode($attribute);
    $js .= "jQuery('#{$form->id}').yiiActiveForm('add', $json);\n";
}
$this->registerJs($js);

Pjax::end();

ActiveForm::end();
