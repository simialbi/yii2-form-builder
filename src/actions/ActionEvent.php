<?php
/**
 * @package yii2-form-builder
 * @author Simon Karlen <simi.albi@gmail.com>
 */

namespace simialbi\yii2\formbuilder\actions;

use yii\base\Event;

class ActionEvent extends Event
{
    /**
     * @var ActionInterface the action currently being executed
     */
    public $action;
    /**
     * @var mixed the action result. Event handlers may modify this property to change the action result.
     */
    public $result;
    /**
     * @var bool whether to continue running the action. Event handlers of
     * [[Controller::EVENT_BEFORE_RUN]] may set this property to decide whether
     * to continue running the current action.
     */
    public $isValid = true;

    /**
     * Constructor.
     * @param ActionInterface $action the action associated with this action event.
     * @param array $config name-value pairs that will be used to initialize the object properties
     */
    public function __construct($action, array $config = [])
    {
        $this->action = $action;
        parent::__construct($config);
    }
}
