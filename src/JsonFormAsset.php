<?php
/**
 * @package yii2-form-builder
 * @author Simon Karlen <simi.albi@gmail.com>
 */

namespace simialbi\yii2\formbuilder;

use simialbi\yii2\web\AssetBundle;

class JsonFormAsset extends AssetBundle
{
    /**
     * @inheritdoc
     */
    public $sourcePath = '@npm/json-editor--json-editor';

    /**
     * @inheritdoc
     */
    public $js = [
        'dist/nonmin/jsoneditor.js'
    ];

    /**
     * @inheritdoc
     */
    public $publishOptions = [
        'only' => [
            'dist/*',
            'dist/nonmin/*',
            'src/*'
        ]
    ];
}
