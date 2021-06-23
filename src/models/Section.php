<?php
/**
 * @package yii2-form-builder
 * @author Simon Karlen <simi.albi@gmail.com>
 */

namespace simialbi\yii2\formbuilder\models;

use Yii;
use yii\behaviors\BlameableBehavior;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * Class Section
 * @package simialbi\yii2\formbuilder\models
 *
 * @property integer $id
 * @property integer $form_id
 * @property string $name
 * @property integer $default_number_of_cols
 * @property integer $order
 * @property integer|string $created_by
 * @property integer|string $updated_by
 * @property integer|string $created_at
 * @property integer|string $updated_at
 *
 * @property-read Form $form
 * @property-read Field[] $fields
 */
class Section extends ActiveRecord
{
    /**
     * {@inheritDoc}
     */
    public static function tableName()
    {
        return '{{%form_builder__section}}';
    }

    /**
     * {@inheritDoc}
     */
    public function rules()
    {
        return [
            [['id', 'form_id', 'order'], 'integer'],
            ['name', 'string', 'max' => 255],
            ['default_number_of_cols', 'integer', 'min' => 2, 'max' => 6],

            ['default_number_of_cols', 'default', 'value' => 2],

            [['form_id', 'name', 'default_number_of_cols'], 'required']
        ];
    }

    /**
     * {@inheritDoc}
     */
    public function behaviors()
    {
        return [
            'blameable' => [
                'class' => BlameableBehavior::class,
                'attributes' => [
                    self::EVENT_BEFORE_INSERT => ['created_by', 'updated_by'],
                    self::EVENT_BEFORE_UPDATE => 'updated_by'
                ]
            ],
            'timestamp' => [
                'class' => TimestampBehavior::class,
                'attributes' => [
                    self::EVENT_BEFORE_INSERT => ['created_at', 'updated_at'],
                    self::EVENT_BEFORE_UPDATE => 'updated_at'
                ]
            ]
        ];
    }

    /**
     * {@inheritDoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('simialbi/formbuilder/models/section', 'Id'),
            'form_id' => Yii::t('simialbi/formbuilder/models/section', 'Form'),
            'name' => Yii::t('simialbi/formbuilder/models/section', 'Name'),
            'default_number_of_cols' => Yii::t('simialbi/formbuilder/models/section', 'Default number of cols'),
            'created_by' => Yii::t('simialbi/formbuilder/models/section', 'Created by'),
            'updated_by' => Yii::t('simialbi/formbuilder/models/section', 'Updated by'),
            'created_at' => Yii::t('simialbi/formbuilder/models/section', 'Created at'),
            'updated_at' => Yii::t('simialbi/formbuilder/models/section', 'Updated at')
        ];
    }

    /**
     * Get associated form
     * @return ActiveQuery
     */
    public function getForm(): ActiveQuery
    {
        return $this->hasOne(Form::class, ['id' => 'form_id']);
    }

    /**
     * Get associated fields
     * @return ActiveQuery
     */
    public function getFields(): ActiveQuery
    {
        return $this->hasMany(Field::class, ['section_id' => 'id']);
    }
}
