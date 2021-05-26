<?php
/**
 * @package yii2-form-builder
 * @author Simon Karlen <simi.albi@gmail.com>
 */

namespace simialbi\yii2\formbuilder;

use simialbi\yii2\formbuilder\models\Form;
use simialbi\yii2\models\UserInterface;
use Yii;
use yii\base\InvalidConfigException;

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
     * Get Form layouts
     *
     * @return array
     */
    public static function getFormLayouts()
    {
        return [
            Form::LAYOUT_DEFAULT => Yii::t('simialbi/formbuilder/layout', 'Default'),
            Form::LAYOUT_FLOATING_LABEL => Yii::t('simialbi/formbuilder/layout', 'Floating labels'),
            Form::LAYOUT_HORIZONTAL => Yii::t('simialbi/formbuilder/layout', 'Horizontal'),
            Form::LAYOUT_PLACEHOLDER => Yii::t('simialbi/formbuilder/layout', 'Placeholders')
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

        parent::init();
    }
}
