<?php

use kartik\select2\Select2;
use marqu3s\summernote\Summernote;
use simialbi\yii2\datedropper\Datedropper;
use simialbi\yii2\timedropper\Timedropper;
use yii\bootstrap4\Html;
use yii\helpers\Json;
use yii\widgets\MaskedInput;

/** @var $this \yii\web\View */
/** @var $form \yii\bootstrap4\ActiveForm */
/** @var $model \yii\base\DynamicModel */
/** @var $field \simialbi\yii2\formbuilder\models\Field */
/** @var $options array */

if ($field->relation_model) {
    $method = 'textInput';
    $params = [];
} else {
    $params = [Json::decode($field->options)];

    switch ($field->type) {
        case $field::TYPE_STRING:
        default:
            if ($field->format) {
                $method = 'widget';
                $opts = Json::decode($field->options);
                if ($field->format === 'email' || $field->format === 'url' || $field->format === 'ip') {
                    $opts['clientOptions']['alias'] = $field->format;
                } else {
                    $opts['mask'] = $field->format;
                }
                $params = [MaskedInput::class, $opts];
            } else {
                $method = 'textInput';
            }
            break;
        case $field::TYPE_RICH_TEXT:
            $method = 'widget';
            $params = [Summernote::class, Json::decode($field->options)];
            break;
        case $field::TYPE_TEXT:
            $method = 'textarea';
            break;
        case $field::TYPE_SELECT:
            $method = 'widget';
            $params = [Select2::class, Json::decode($field->options)];
            $id = Html::getInputId($model, $field->name);
            $js = <<<JS
jQuery('#$id').on({
    'select2:select': function () {
        jQuery(this).addClass('selected');
    },
    'select2:unselect': function () {
        jQuery(this).removeClass('selected');
    }
});
JS;
            $this->registerJs($js);
            break;
        case $field::TYPE_DATE:
            $method = 'widget';
            $params = [Datedropper::class, Json::decode($field->options)];
            break;
        case $field::TYPE_TIME;
            $method = 'widget';
            $params = [Timedropper::class, Json::decode($field->options)];
            break;
        case $field::TYPE_INT:
        case $field::TYPE_DOUBLE:
            break;
        case $field::TYPE_FILE:
            $method = 'fileInput';
            break;
        case $field::TYPE_CHECKBOX:
            $method = 'checkbox';
            break;
        case $field::TYPE_RADIO:
            $method = 'radio';
            break;
    }
}
Html::addCssClass($options, ['form-group', 'col']);
$fld = $form->field($model, $field->name, [
    'options' => $options
]);
echo call_user_func_array([$fld, $method], $params);
