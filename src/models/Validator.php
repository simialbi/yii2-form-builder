<?php
/**
 * @package yii2-form-builder
 * @author Simon Karlen <simi.albi@gmail.com>
 */

namespace simialbi\yii2\formbuilder\models;

use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * Class Validator
 * @package simialbi\yii2\formbuilder\models
 *
 * @property integer $id
 * @property integer $field_id
 * @property string $name
 * @property string $class
 * @property string|array $configuration
 *
 * @property-read Field $field
 */
class Validator extends ActiveRecord
{
    /**
     * {@inheritDoc}
     */
    public static function tableName()
    {
        return '{{%form_builder__validator}}';
    }

    /**
     * {@inheritDoc}
     */
    public function rules()
    {
        return [
            [['id', 'field_id'], 'integer'],
            ['name', 'string', 'max' => 255],
            ['class', 'string', 'max' => 512, 'encoding' => 'ASCII'],
            ['configuration', 'string'],

            [['name', 'field_id', 'class'], 'required']
        ];
    }

    /**
     * Get associated field
     * @return ActiveQuery
     */
    public function getField(): ActiveQuery
    {
        return $this->hasOne(Field::class, ['id' => 'field_id']);
    }
}
