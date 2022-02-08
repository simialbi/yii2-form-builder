<?php
/**
 * @package yii2-form-builder
 * @author Simon Karlen <simi.albi@gmail.com>
 */

namespace simialbi\yii2\formbuilder\models;

use Yii;
use yii\db\ActiveRecord;

/**
 * Class Action
 * @package simialbi\yii2\formbuilder\models
 *
 * @property integer $id
 * @property string $name
 * @property string $translation_category
 * @property string $class
 * @property string $properties
 *
 * @property-read string $translatedName
 */
class Action extends ActiveRecord
{
    /**
     * {@inheritDoc}
     */
    public static function tableName(): string
    {
        return '{{%form_builder__action}}';
    }

    /**
     * {@inheritDoc}
     */
    public function rules(): array
    {
        return [
            ['id', 'integer'],
            [['name', 'translation_category'], 'string', 'max' => 255],
            ['class', 'string', 'max' => 512, 'encoding' => 'us-ascii'],
            ['properties', 'string'],

            ['translation_category', 'default'],
            ['properties', 'default', 'value' => '{}'],

            ['name', 'required']
        ];
    }

    /**
     * {@inheritDoc}
     */
    public function attributeLabels(): array
    {
        return [
            'id' => Yii::t('simialbi/formbuilder/models/action', 'Id'),
            'name' => Yii::t('simialbi/formbuilder/models/action', 'Name'),
            'translation_category' => Yii::t('simialbi/formbuilder/models/action', 'Translation category'),
            'class' => Yii::t('simialbi/formbuilder/models/action', 'Class'),
            'properties' => Yii::t('simialbi/formbuilder/models/action', 'Properties')
        ];
    }

    /**
     * Get translated name of action
     * @return string
     */
    public function getTranslatedName(): string
    {
        return Yii::t(
            empty($this->translation_category) ? 'simialbi/formbuilder/action' : $this->translation_category,
            $this->name
        );
    }
}
