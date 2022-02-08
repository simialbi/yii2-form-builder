<?php
/**
 * @package yii2-form-builder
 * @author Simon Karlen <simi.albi@gmail.com>
 */

namespace simialbi\yii2\formbuilder\behaviors;

use yii\base\Behavior;
use yii\db\ActiveRecord;
use yii\helpers\Json;

/**
 * ConfigurableBehavior automatically transforms a specified value to one or multiple attributes of an ActiveRecord
 * object when certain events happen.
 *
 * To use ConfigurableBehavior, configure the [[attributes]] property which should specify the list of attributes
 * that need to be updated and the corresponding events that should trigger the update.
 *
 * ```php
 * use yii\behaviors\AttributeBehavior;
 *
 * public function behaviors()
 * {
 *     return [
 *         [
 *             'class' => AttributeBehavior::class,
 *             'attributes' => [
 *                 ActiveRecord::EVENT_BEFORE_INSERT => 'attribute1',
 *                 ActiveRecord::EVENT_BEFORE_UPDATE => 'attribute2',
 *             ]
 *         ],
 *     ];
 * }
 * ```
 */
class ConfigurableBehavior extends Behavior
{
    /**
     * @var array list of attributes that are to be automatically filled with the value specified via [[value]].
     * The array keys are the ActiveRecord events upon which the attributes are to be updated,
     * and the array values are the corresponding attribute(s) to be updated. You can use a string to represent
     * a single attribute, or an array to represent a list of attributes. For example,
     *
     * ```php
     * [
     *     ActiveRecord::EVENT_BEFORE_INSERT => ['attribute1', 'attribute2'],
     *     ActiveRecord::EVENT_BEFORE_UPDATE => 'attribute2',
     * ]
     * ```
     */
    public $attributes = [];
    /**
     * @var bool whether to skip this behavior when the `$owner` has not been
     * modified
     * @since 2.0.8
     */
    public $skipUpdateOnClean = true;
    /**
     * @var bool whether to preserve non-empty attribute values.
     * @since 2.0.13
     */
    public $preserveNonEmptyValues = false;


    /**
     * {@inheritdoc}
     */
    public function events(): array
    {
        return array_fill_keys(
            array_keys($this->attributes),
            'evaluateAttributes'
        );
    }

    /**
     * Evaluates the attribute value and assigns it to the current attributes.
     * @param \yii\base\Event $event
     */
    public function evaluateAttributes(\yii\base\Event $event)
    {
        if ($this->skipUpdateOnClean
            && $event->name == ActiveRecord::EVENT_BEFORE_UPDATE
            && empty($this->owner->dirtyAttributes)
        ) {
            return;
        }

        if (!empty($this->attributes[$event->name])) {
            $attributes = (array)$this->attributes[$event->name];
            foreach ($attributes as $attribute) {
                $value = $this->getValue($event, $attribute);
                // ignore attribute names which are not string (e.g. when set by TimestampBehavior::updatedAtAttribute)
                if (is_string($attribute)) {
                    if ($this->preserveNonEmptyValues && !empty($this->owner->$attribute)) {
                        continue;
                    }
                    $this->owner->$attribute = $value;
                }
            }
        }
    }

    /**
     * Returns the value for the current attributes.
     * This method is called by [[evaluateAttributes()]]. Its return value will be assigned
     * to the attributes corresponding to the triggering event.
     * @param \yii\base\Event $event the event that triggers the current attribute updating.
     * @param string $attribute The attribute to get value for.
     * @return mixed the attribute value
     */
    protected function getValue(\yii\base\Event $event, string $attribute)
    {
        $config = $this->owner->$attribute;
        if (is_array($config)) {
            foreach ($config as $key => $value) {
                if ($value === '') {
                    unset($config[$key]);
                } elseif ($value === 'off') {
                    $config[$key] = false;
                } elseif ($value === 'on') {
                    $config[$key] = true;
                }
            }
            $config = Json::encode($config);
        }

        return $config;
    }
}
