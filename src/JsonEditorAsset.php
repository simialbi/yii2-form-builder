<?php
/**
 * @package yii2-form-builder
 * @author Simon Karlen <simi.albi@gmail.com>
 */

namespace simialbi\yii2\formbuilder;

use simialbi\yii2\web\AssetBundle;

class JsonEditorAsset extends AssetBundle
{
    /**
     * @inheritdoc
     */
    public $sourcePath = '@npm/jsoneditor/dist';

    /**
     * @inheritdoc
     */
    public $css = ['jsoneditor.css'];

    /**
     * @inheritdoc
     */
    public $js = ['jsoneditor.js'];
}
