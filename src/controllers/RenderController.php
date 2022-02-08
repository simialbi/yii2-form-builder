<?php
/**
 * @package yii2-form-builder
 * @author Simon Karlen <simi.albi@gmail.com>
 */

namespace simialbi\yii2\formbuilder\controllers;

use simialbi\yii2\formbuilder\models\Form;
use Yii;
use yii\base\DynamicModel;
use yii\helpers\Inflector;
use yii\helpers\Json;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

class RenderController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public $defaultAction = 'form';

    /**
     * @param integer $id
     *
     * @return string
     *
     * @throws NotFoundHttpException|\yii\base\InvalidConfigException
     */
    public function actionForm(int $id): string
    {
        $form = $this->findForm($id);
        $model = new DynamicModel();
        $relations = [];

        foreach ($form->fields as $field) {
            $model->defineAttribute($field->name);
            $model->setAttributeLabel($field->name, $field->label);

            if (!empty($field->relation_model) && class_exists($field->relation_model) && !isset($relations[$field->relation_model])) {
                /** @var \yii\db\ActiveQuery $relation */
                $relation = call_user_func([$field->relation_model, 'find']);
                $relations[$field->relation_model] = $relation->all();
            }

            foreach ($field->fieldValidators as $validator) {
                $model->addRule($field->name, $validator->class, Json::decode($validator->configuration));
            }
        }

        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            foreach ($form->formActions as $formAction) {
                if (!class_exists($formAction->action->class)) {
                    // TODO: Log
                    continue;
                }
                /** @var \simialbi\yii2\formbuilder\actions\BaseAction $action */
                $configuration = Json::decode($formAction->configuration);
                $configuration['class'] = $formAction->action->class;
                $action = Yii::createObject($configuration);

                $action->runWithParams([$form, $model]);
            }
        }

        return $this->render('form', [
            'model' => $model,
            'name' => $form->name,
            'layout' => Inflector::camel2id($form->layout),
            'sections' => $form->sections,
            'relations' => $relations
        ]);
    }


    /**
     * Finds the model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     *
     * @param mixed $condition primary key value or a set of column values
     *
     * @return Form the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findForm($condition): Form
    {
        if (($model = Form::findOne($condition)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException(Yii::t('yii', 'Page not found.'));
        }
    }
}
