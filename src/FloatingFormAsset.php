<?php
/**
 * @package yii2-form-builder
 * @author Simon Karlen <simi.albi@gmail.com>
 */

namespace simialbi\yii2\formbuilder;

use simialbi\yii2\web\AssetBundle;

class FloatingFormAsset extends AssetBundle
{
    /**
     * @inheritdoc
     */
    public $css = [
        'css/floating-form.css'
    ];

    /**
     * @inheritdoc
     */
    public $depends = [];
}
