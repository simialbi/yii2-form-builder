<?php
/**
 * @package yii2-form-builder
 * @author Simon Karlen <simi.albi@gmail.com>
 */

namespace simialbi\yii2\formbuilder;

use simialbi\yii2\formbuilder\models\Field;
use simialbi\yii2\formbuilder\models\Form;
use simialbi\yii2\models\UserInterface;
use Yii;
use yii\base\InvalidConfigException;
use yii\db\ActiveRecordInterface;
use yii\helpers\StringHelper;

/**
 * Class Module
 * @package simialbi\yii2\formbuilder
 */
class Module extends \simialbi\yii2\base\Module
{
    /**
     * {@inheritdoc}
     */
    public $defaultRoute = 'builder';

    /**
     * @var array The languages the user can use in form builder. The key's are the ISO-639-1 codes optionally combined
     * with the ISO 3166 ALPHA-2 codes of the country (e.g. `de-DE`).
     *
     * Example:
     * ```php
     * [
     *     'en-US' => 'English (United States)',
     *     'de-DE' => 'Deutsch (Deutschland)'
     * ]
     * ```
     */
    public $languages = [];

    /**
     * @var array A list of full qualified class names which are allowed to relate to in form fields. Each of them
     * must implement the [[\yii\db\ActiveRecordInterface]].
     *
     * Example:
     * ```php
     * [
     *     '\app\models\User',
     *     '\app\models\Post',
     *     '\app\models\Comment'
     * ]
     * ```
     */
    public $relationClasses = [];

    /**
     * Get Form layouts
     *
     * @return array
     */
    public static function getFormLayouts(): array
    {
        return [
            Form::LAYOUT_DEFAULT => Yii::t('simialbi/formbuilder/layout', 'Default'),
            Form::LAYOUT_FLOATING_LABEL => Yii::t('simialbi/formbuilder/layout', 'Floating labels'),
            Form::LAYOUT_HORIZONTAL => Yii::t('simialbi/formbuilder/layout', 'Horizontal'),
            Form::LAYOUT_PLACEHOLDER => Yii::t('simialbi/formbuilder/layout', 'Placeholders')
        ];
    }

    /**
     * Get Field types
     *
     * @return array
     */
    public static function getFieldTypes(): array
    {
        return [
            Field::TYPE_STRING => Yii::t('simialbi/formbuilder/field-type', 'String'),
            Field::TYPE_TEXT => Yii::t('simialbi/formbuilder/field-type', 'Text'),
            Field::TYPE_INT => Yii::t('simialbi/formbuilder/field-type', 'Integer'),
            Field::TYPE_DOUBLE => Yii::t('simialbi/formbuilder/field-type', 'Double'),
            Field::TYPE_DATE => Yii::t('simialbi/formbuilder/field-type', 'Date'),
            Field::TYPE_TIME => Yii::t('simialbi/formbuilder/field-type', 'Time'),
            Field::TYPE_DATETIME => Yii::t('simialbi/formbuilder/field-type', 'Date time'),
            Field::TYPE_SELECT => Yii::t('simialbi/formbuilder/field-type', 'Select'),
            Field::TYPE_CHECKBOX => Yii::t('simialbi/formbuilder/field-type', 'Checkbox'),
            Field::TYPE_RADIO => Yii::t('simialbi/formbuilder/field-type', 'Radio'),
            Field::TYPE_FILE => Yii::t('simialbi/formbuilder/field-type', 'File')
        ];
    }

    /**
     * Get dependency operators
     *
     * @return array
     */
    public static function getDependencyOperators(): array
    {
        return [
            Field::OPERATOR_NOT => Yii::t('simialbi/formbuilder/dependency-operator', 'Not'),
            Field::OPERATOR_GT => Yii::t('simialbi/formbuilder/dependency-operator', 'Grater than'),
            Field::OPERATOR_LT => Yii::t('simialbi/formbuilder/dependency-operator', 'Lower than'),
            Field::OPERATOR_EQ => Yii::t('simialbi/formbuilder/dependency-operator', 'Equal')
        ];
    }

    /**
     * {@inheritDoc}
     * @throws InvalidConfigException
     */
    public function init()
    {
        $this->registerTranslations();

        $identity = new Yii::$app->user->identityClass;
        if (!($identity instanceof UserInterface)) {
            throw new InvalidConfigException('The "identityClass" must extend "simialbi\yii2\models\UserInterface"');
        }
        if (!Yii::$app->hasModule('gridview')) {
            $this->setModule('gridview', [
                'class' => 'kartik\grid\Module',
                'exportEncryptSalt' => 'ror_HTbRh0Ad7K7DqhAtZOp50GKyia4c',
                'i18n' => [
                    'class' => 'yii\i18n\PhpMessageSource',
                    'basePath' => '@kvgrid/messages',
                    'forceTranslation' => true
                ]
            ]);
        }
        foreach ($this->relationClasses as $k => $className) {
            unset($this->relationClasses[$k]);
            if (!class_exists($className)) {
                Yii::warning("Class `$className` does not exists!", __METHOD__);
                continue;
            }
            /** @var \yii\db\ActiveRecordInterface $class */
            $class = new $className;
            if (!$class instanceof ActiveRecordInterface) {
                throw new InvalidConfigException("Class `$className` must implement `ActiveRecordInteface`", __METHOD__);
            }

            $this->relationClasses[$className] = [
                'name' => StringHelper::basename($className),
                'attributes' => array_combine($class->attributes(), array_map([$class, 'getAttributeLabel'], $class->attributes()))
            ];
        }

        parent::init();
    }
}
