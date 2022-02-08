<?php

use simialbi\yii2\formbuilder\FloatingFormAsset;

/** @var $this \yii\web\View */
/** @var $form \yii\bootstrap4\ActiveForm */
/** @var $model \yii\base\DynamicModel */
/** @var $sections \simialbi\yii2\formbuilder\models\Section[] */
/** @var $relations \yii\db\ActiveRecord[] */

/**
 * @param \yii\base\DynamicModel $model
 * @param string $attribute
 * @return array
 */
$form->fieldConfig = function (\yii\base\DynamicModel $model, string $attribute) {
    return [
        'template' => "{input}\n{label}\n{hint}\n{error}",
        'inputOptions' => [
            'placeholder' => $model->getAttributeLabel($attribute)
        ]
    ];
};

FloatingFormAsset::register($this);
?>

<?php foreach ($sections as $section): ?>
    <div class="sa-formbuilder-section card">
        <div class="card-header">
            <h4 class="card-title mb-0"><?= $section->name; ?></h4>
        </div>
        <div class="card-body">
            <div class="form-row">
                <?php for ($i = 0; $i < count($section->fields); $i++): ?>
                    <?php $field = $section->fields[$i]; ?>
                    <?= $this->render('_field', [
                        'field' => $field,
                        'form' => $form,
                        'section' => $section,
                        'model' => $model,
                        'options' => ['class' => 'form-floating'],
                        'relations' => $relations
                    ]); ?>
                <?php endfor; ?>
            </div>
        </div>
    </div>
<?php endforeach; ?>
