<?php
/**
 * @package yii2-form-builder
 * @author Simon Karlen <simi.albi@gmail.com>
 */

namespace simialbi\yii2\formbuilder\models;

use simialbi\yii2\formbuilder\behaviors\ConfigurableBehavior;
use Yii;
use yii\db\ActiveRecord;

/**
 * Class FormAction
 * @package simialbi\yii2\formbuilder\models
 *
 * @property integer $form_id
 * @property integer $action_id
 * @property string $configuration
 *
 * @property-read Form $form
 * @property-read Action $action
 */
class FormAction extends ActiveRecord
{
    /**
     * {@inheritDoc}
     */
    public static function tableName(): string
    {
        return '{{%form_builder__form_action}}';
    }

    /**
     * {@inheritDoc}
     */
    public function rules(): array
    {
        return [
            [['form_id', 'action_id'], 'integer', 'min' => 1],
            ['configuration', 'string'],

            ['configuration', 'default'],

            [['form_id', 'action_id'], 'required']
        ];
    }

    /**
     * {@inheritDoc}
     */
    public function behaviors(): array
    {
        return [
            'configurable' => [
                'class' => ConfigurableBehavior::class,
                'attributes' => [
                    self::EVENT_BEFORE_VALIDATE => 'configuration'
                ]
            ]
        ];
    }

    /**
     * {@inheritDoc}
     */
    public function attributeLabels(): array
    {
        return [
            'form_id' => Yii::t('simialbi/formbuilder/models/form-action', 'Form'),
            'action_id' => Yii::t('simialbi/formbuilder/models/form-action', 'Action'),
            'configuration' => Yii::t('simialbi/formbuilder/models/form-action', 'Configuration')
        ];
    }

    /**
     * Get associated form
     * @return \yii\db\ActiveQuery
     */
    public function getForm(): \yii\db\ActiveQuery
    {
        return $this->hasOne(Form::class, ['id' => 'form_id']);
    }

    /**
     * Get associated action
     * @return \yii\db\ActiveQuery
     */
    public function getAction(): \yii\db\ActiveQuery
    {
        return $this->hasOne(Action::class, ['id' => 'action_id']);
    }
}
