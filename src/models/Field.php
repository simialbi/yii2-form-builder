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
 * Class Field
 * @package simialbi\yii2\formbuilder\models
 *
 * @property integer $id
 * @property integer $section_id
 * @property string $name
 * @property string $label
 * @property string $type
 * @property string $default_value
 * @property boolean $multiple
 * @property integer $min
 * @property integer $max
 * @property string $relation_model
 * @property string $relation_field
 * @property string $relation_display_template
 * @property integer $order
 * @property integer|string $created_by
 * @property integer|string $updated_by
 * @property integer|string $created_at
 * @property integer|string $updated_at
 *
 * @property-read Section $section
 * @property-read Form $form
 * @property-read Validator[] $fieldValidators
 */
class Field extends ActiveRecord
{
    const TYPE_STRING = 'string';
    const TYPE_TEXT = 'text';
    const TYPE_RICH_TEXT = 'richText';
    const TYPE_INT = 'int';
    const TYPE_DOUBLE = 'double';
    const TYPE_DATE = 'date';
    const TYPE_TIME = 'time';
    const TYPE_DATETIME = 'datetime';
    const TYPE_SELECT = 'select';
    const TYPE_CHECKBOX = 'checkbox';
    const TYPE_RADIO = 'radio';
    const TYPE_FILE = 'file';

    /**
     * {@inheritDoc}
     */
    public static function tableName()
    {
        return '{{%form_builder__field}}';
    }

    /**
     * {@inheritDoc}
     */
    public function rules()
    {
        return [
            [['id', 'section_id', 'min', 'max', 'order'], 'integer'],
            [['name', 'label', 'default_value'], 'string', 'max' => 255],
            [
                'type',
                'in',
                'range' => [
                    static::TYPE_STRING,
                    static::TYPE_TEXT,
                    static::TYPE_RICH_TEXT,
                    static::TYPE_INT,
                    static::TYPE_DOUBLE,
                    static::TYPE_DATE,
                    static::TYPE_TIME,
                    static::TYPE_DATETIME,
                    static::TYPE_SELECT,
                    static::TYPE_CHECKBOX,
                    static::TYPE_RADIO,
                    static::TYPE_FILE
                ]
            ],
            [['multiple'], 'boolean'],
            ['relation_field', 'string', 'max' => 255, 'encoding' => 'ASCII'],
            ['relation_model', 'string', 'max' => 512, 'encoding' => 'ASCII'],
            ['relation_display_template', 'string', 'max' => 1024],

            ['type', 'default', 'value' => static::TYPE_STRING],
            [['multiple'], 'default', 'value' => false],

            [['section_id', 'name', 'type', 'multiple'], 'required']
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
            'id' => Yii::t('simialbi/formbuilder/models/field', 'Id'),
            'section_id' => Yii::t('simialbi/formbuilder/models/field', 'Section'),
            'name' => Yii::t('simialbi/formbuilder/models/field', 'Name'),
            'label' => Yii::t('simialbi/formbuilder/models/field', 'Label'),
            'type' => Yii::t('simialbi/formbuilder/models/field', 'Type'),
            'default_value' => Yii::t('simialbi/formbuilder/models/field', 'Default value'),
            'multiple' => Yii::t('simialbi/formbuilder/models/field', 'Multiple'),
            'min' => Yii::t('simialbi/formbuilder/models/field', 'Min'),
            'max' => Yii::t('simialbi/formbuilder/models/field', 'Max'),
            'relation_model' => Yii::t('simialbi/formbuilder/models/field', 'Relation model'),
            'relation_field' => Yii::t('simialbi/formbuilder/models/field', 'Relation field'),
            'relation_display_template' => Yii::t('simialbi/formbuilder/models/field', 'Relation display template'),
            'created_by' => Yii::t('simialbi/formbuilder/models/field', 'Created by'),
            'updated_by' => Yii::t('simialbi/formbuilder/models/field', 'Updated by'),
            'created_at' => Yii::t('simialbi/formbuilder/models/field', 'Created at'),
            'updated_at' => Yii::t('simialbi/formbuilder/models/field', 'Updated at')
        ];
    }

    /**
     * Get associated section
     * @return ActiveQuery
     */
    public function getSection(): ActiveQuery
    {
        return $this->hasOne(Section::class, ['id' => 'section_id']);
    }

    /**
     * Get associated form
     * @return ActiveQuery
     */
    public function getForm(): ActiveQuery
    {
        return $this->hasOne(Form::class, ['id' => 'section_id'])->via('section');
    }

    /**
     * Get associated field validators
     * @return ActiveQuery
     */
    public function getFieldValidators(): ActiveQuery
    {
        return $this->hasMany(Validator::class, ['field_id' => 'id']);
    }
}
