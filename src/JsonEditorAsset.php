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
    public $sourcePath = '@npm/json-editor--json-editor';

    /**
     * @inheritdoc
     */
    public $js = [
        'dist/jsoneditor.js'
    ];

    /**
     * @inheritdoc
     */
    public $publishOptions = [
        'only' => [
            'dist/*',
            'src/*'
        ]
    ];
}
