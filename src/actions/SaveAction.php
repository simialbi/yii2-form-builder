<?php
/**
 * @package yii2-form-builder
 * @author Simon Karlen <simi.albi@gmail.com>
 */

namespace simialbi\yii2\formbuilder\actions;

use simialbi\yii2\formbuilder\models\Form;
use yii\base\DynamicModel;
use yii\base\InvalidConfigException;
use yii\di\Instance;
use yii\helpers\StringHelper;

/**
 * Save action gets the data from a [[Form]] build by form builder and saves it via an ActiveRecord model to the
 * database.
 */
class SaveAction extends BaseAction implements ActionInterface
{
    /**
     * @var string|\yii\db\ActiveRecord The model used to save the data.
     */
    public $model;

    /**
     * @var array The field mapping of the form attributes to the model attributes. The keys represents the form
     * attributes and the values the corresponding model attributes.
     */
    public $fields = [];

    /**
     * {@inheritDoc}
     * @throws InvalidConfigException
     */
    public function init()
    {
        $this->model = Instance::ensure($this->model);
        if (!($this->model instanceof \yii\db\ActiveRecord)) {
            throw new InvalidConfigException('Class ' . StringHelper::basename(get_class($this->model)) . ' must extend `yii\db\ActiveRecord`.');
        }
        parent::init();
    }

    /**
     * {@inheritDoc}
     */
    public function run(Form $form, DynamicModel $model): bool
    {
        foreach ($this->fields as $from => $to) {
            if ($to !== 'undefined') {
                $this->model->setAttribute($to, $model->$from);
            }
        }
        $attributes = array_filter(array_values($this->fields), function ($item) {
            return $item !== 'undefined';
        });

        return $this->model->save(true, $attributes);
    }
}
