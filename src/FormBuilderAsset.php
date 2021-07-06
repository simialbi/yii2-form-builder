<?php
/**
 * @package yii2-form-builder
 * @author Simon Karlen <simi.albi@gmail.com>
 */

namespace simialbi\yii2\formbuilder;

use simialbi\yii2\web\AssetBundle;

class FormBuilderAsset extends AssetBundle
{
    /**
     * @inheritdoc
     */
    public $js = [
        'js/form-builder.js'
    ];

    /**
     * @inheritdoc
     */
    public $depends = [
        '\yii\web\YiiAsset',
        '\yii\jui\JuiAsset',
        '\simialbi\yii2\formbuilder\JsonEditorAsset',
        '\simialbi\yii2\formbuilder\JsonFormAsset'
    ];
}
