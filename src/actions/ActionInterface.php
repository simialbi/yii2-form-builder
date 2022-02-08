<?php
/**
 * @package yii2-form-builder
 * @author Simon Karlen <simi.albi@gmail.com>
 */

namespace simialbi\yii2\formbuilder\actions;

use simialbi\yii2\formbuilder\models\Form;
use yii\base\DynamicModel;
use yii\base\InvalidConfigException;
use yii\web\ServerErrorHttpException;

/**
 * Action interface is the interface which should be implemented by submit actions for forms created with form builder.
 *
 * The main method [[run()]] will be invoked by the form controller with the submitted form and model as parameters.
 *
 *
 */
interface ActionInterface
{
    /**
     * Initializes the action.
     * This method is invoked at the end of the constructor after the object is initialized with the
     * given configuration.
     *
     * @throws InvalidConfigException on missing or wrong configured parameters.
     * @throws ServerErrorHttpException on failure.
     */
    public function init();

    /**
     * Runs the action
     *
     * @param Form $form The [[Form]] instance submitted by the user
     * @param DynamicModel $model The [[DynamicModel]] instance holding the validators, labels, attributes and data.
     *
     * @return boolean `true` on success, otherwise `false`.
     */
    public function run(Form $form, DynamicModel $model): bool;
}
