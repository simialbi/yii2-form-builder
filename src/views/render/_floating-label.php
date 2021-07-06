<?php

/** @var $this \yii\web\View */
/** @var $form \yii\bootstrap4\ActiveForm */
/** @var $model \yii\base\DynamicModel */
/** @var $sections \simialbi\yii2\formbuilder\models\Section[] */

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
                        'model' => $model
                    ]); ?>
                    <?php if (($i + 1) % $section->default_number_of_cols === 0): ?>
            </div>
            <div class="form-row">
                    <?php endif; ?>
                <?php endfor; ?>
            </div>
        </div>
    </div>
<?php endforeach; ?>
