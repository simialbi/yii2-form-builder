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
/** @var $section \simialbi\yii2\formbuilder\models\Section */
/** @var $model \yii\base\DynamicModel */
/** @var $field \simialbi\yii2\formbuilder\models\Field */
/** @var $options array */
/** @var $relations \yii\db\ActiveRecord[] */

if ($field->relation_model) {
    if (isset($relations[$field->relation_model])) {
        $data = [];
        /** @var \yii\db\ActiveRecord $relation */
        foreach ($relations[$field->relation_model] as $relation) {
            $data[$relation->getAttribute($field->relation_field)] = preg_replace_callback('#\{([^\}]+)\}#', function ($matches) use ($relation) {
                return $relation->hasAttribute($matches[1]) ? $relation->getAttribute($matches[1]) : '';
            }, $field->relation_display_template);
        }
        asort($data);
        $method = 'widget';
        $params = [Select2::class, [
            'data' => $data,
            'theme' => Select2::THEME_KRAJEE_BS4,
            'bsVersion' => 4,
            'options' => [
                'placeholder' => '',
                'multiple' => false
            ],
            'pluginOptions' => [
                'allowClear' => !$model->isAttributeRequired($field->name)
            ]
        ]];
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
    } else {
        $method = 'textInput';
        $params = [];
    }
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
if (!empty($field->number_of_cols)) {
    Html::addCssClass($options, ['col-12', 'col-lg-' . $field->number_of_cols]);
} else {
    Html::addCssClass($options, ['col-12', 'col-lg-' . floor(12 / $section->default_number_of_cols)]);
}
Html::addCssClass($options, 'form-group');
$fld = $form->field($model, $field->name, [
    'options' => $options
]);
echo call_user_func_array([$fld, $method], $params);
