<?php

/** @var $this \yii\web\View */
/** @var $form \yii\bootstrap4\ActiveForm */
/** @var $model \yii\base\DynamicModel */
/** @var $field \simialbi\yii2\formbuilder\models\Field */

use marqu3s\summernote\Summernote;

switch ($field->type) {
    case $field::TYPE_STRING:
    default:
        $method = 'textInput';
        $params = [];
        break;
    case $field::TYPE_RICH_TEXT:
        $method = 'widget';
        $params = [Summernote::class, []];
        break;
    case $field::TYPE_TEXT:
        $method = 'textarea';
        $params = [['rows' => 5]];
        break;
    case $field::TYPE_SELECT:
        $method = 'dropdownList';
        $params = [];
        break;
    case $field::TYPE_DATE:
        $method = 'textInput';
        $params = [['type' =>  'date-local']];
        break;
    case $field::TYPE_TIME;
        $params = [['type' =>  'time-local']];
        break;
    case $field::TYPE_FILE:
        $method = 'fileInput';
        $params = [];
        break;
    case $field::TYPE_DOUBLE:
    case $field::TYPE_INT:
        $method = 'textInput';
        $params = [['type' => 'number']];
        if ($field->type === $field::TYPE_DOUBLE) {
            $params[0]['step'] = '0.1';
        }
        break;
    case $field::TYPE_CHECKBOX:
        $method = 'checkbox';
        $params = [];
        break;
    case $field::TYPE_RADIO:
        $method = 'radio';
        $params = [];
        break;
}
$fld = $form->field($model, $field->name, [
    'options' => [
        'class' => ['form-group', 'col']
    ]
]);
echo call_user_func_array([$fld, $method], $params);
