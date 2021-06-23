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
 * Class Form
 * @package simialbi\yii2\formbuilder\models
 *
 * @property integer $id
 * @property string $name
 * @property string $layout
 * @property string $language
 * @property integer|string $created_by
 * @property integer|string $updated_by
 * @property integer|string $created_at
 * @property integer|string $updated_at
 *
 * @property-read Section[] $sections
 * @property-read Field[] $fields
 */
class Form extends ActiveRecord
{
    const LAYOUT_DEFAULT = 'default';
    const LAYOUT_FLOATING_LABEL = 'floatingLabel';
    const LAYOUT_PLACEHOLDER = 'placeholder';
    const LAYOUT_HORIZONTAL = 'horizontal';

    /**
     * {@inheritDoc}
     */
    public static function tableName()
    {
        return '{{%form_builder__form}}';
    }

    /**
     * {@inheritDoc}
     */
    public function rules()
    {
        return [
            ['id', 'integer'],
            ['name', 'string', 'max' => 255],
            [
                'layout',
                'in',
                'range' => [
                    static::LAYOUT_DEFAULT,
                    static::LAYOUT_FLOATING_LABEL,
                    static::LAYOUT_PLACEHOLDER,
                    static::LAYOUT_HORIZONTAL
                ]
            ],
            ['language', 'string', 'min' => 2, 'max' => 5],

            ['layout', 'default', 'value' => static::LAYOUT_DEFAULT],
            ['language', 'default', 'value' => Yii::$app->language],

            [['name', 'layout', 'language'], 'required']
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
            'id' => Yii::t('simialbi/formbuilder/model/Form', 'Id'),
            'name' => Yii::t('simialbi/formbuilder/model/Form', 'Name'),
            'layout' => Yii::t('simialbi/formbuilder/model/Form', 'Layout'),
            'language' => Yii::t('simialbi/formbuilder/model/Form', 'Language'),
            'created_by' => Yii::t('simialbi/formbuilder/model/Form', 'Created by'),
            'updated_by' => Yii::t('simialbi/formbuilder/model/Form', 'Updated by'),
            'created_at' => Yii::t('simialbi/formbuilder/model/Form', 'Created at'),
            'updated_at' => Yii::t('simialbi/formbuilder/model/Form', 'Updated at')
        ];
    }

    /**
     * Get associated section
     * @return ActiveQuery
     */
    public function getSections(): ActiveQuery
    {
        return $this->hasMany(Section::class, ['form_id' => 'id']);
    }

    /**
     * Get associated fields
     * @return ActiveQuery
     */
    public function getFields(): ActiveQuery
    {
        return $this->hasMany(Field::class, ['section_id' => 'id'])->via('section');
    }
}
