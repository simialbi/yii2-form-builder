<?php
/**
 * @package yii2-form-builder
 * @author Simon Karlen <simi.albi@gmail.com>
 */

namespace simialbi\yii2\formbuilder\actions;

use Yii;
use yii\base\Component;
use yii\base\InvalidConfigException;

abstract class BaseAction extends Component implements ActionInterface
{
    /**
     * @event Event an event that is triggered when the action is initialized via [[init()]].
     */
    const EVENT_INIT = 'init';

    /**
     * @event ActionEvent an event that is triggered before action is run.
     * You may set [[ActionEvent::isValid]] to be `false` to stop the running the action.
     */
    const EVENT_BEFORE_RUN = 'beforeRun';

    /**
     * @event ActionEvent an event that is triggered after an action is run.
     */
    const EVENT_AFTER_RUN = 'afterRun';

    /**
     * {@inheritDoc}
     */
    public function init()
    {
        parent::init();
        $this->trigger(self::EVENT_INIT);
    }

    /**
     * Runs this action with the specified parameters.
     * This method is mainly invoked by the controller.
     *
     * @param array $params the parameters to be bound to the action's run() method.
     * @return boolean the result of the action
     * @throws InvalidConfigException if the action class does not have a run() method
     */
    public function runWithParams(array $params): bool
    {
        if (!method_exists($this, 'run')) {
            throw new InvalidConfigException(get_class($this) . ' must define a "run()" method.');
        }

        Yii::debug('Running action: ' . get_class($this) . '::run()', __METHOD__);
        if (Yii::$app->requestedParams === null) {
            Yii::$app->requestedParams = $params;
        }
        if ($this->beforeRun()) {
            $result = call_user_func_array([$this, 'run'], $params);
            $this->afterRun($result);

            return $result;
        }

        return false;
    }

    /**
     * This method is called right before `run()` is executed.
     * You may override this method to do preparation work for the action run.
     * If the method returns false, it will cancel the action.
     *
     * @return bool whether to run the action.
     */
    protected function beforeRun(): bool
    {
        $event = new ActionEvent($this);
        $this->trigger(self::EVENT_BEFORE_RUN, $event);
        return $event->isValid;
    }

    /**
     * This method is called right after `run()` is executed.
     * You may override this method to do post-processing work for the action run.
     *
     * @param mixed $result
     */
    protected function afterRun($result)
    {
        $event = new ActionEvent($this);
        $event->result = $result;
        $this->trigger(self::EVENT_AFTER_RUN, $event);
    }
}
