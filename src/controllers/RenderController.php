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
     * @throws NotFoundHttpException
     */
    public function actionForm(int $id)
    {
        $form = $this->findForm($id);
        $model = new DynamicModel();

        foreach ($form->fields as $field) {
            $model->defineAttribute($field->name);
            $model->setAttributeLabel($field->name, $field->label);
            foreach ($field->fieldValidators as $validator) {
                $model->addRule($field->name, $validator->class, Json::decode($validator->configuration));
            }
        }

        if ($model->load(Yii::$app->request->post()) && $model->validate()) {

        }

        return $this->render('form', [
            'model' => $model,
            'name' => $form->name,
            'layout' => Inflector::camel2id($form->layout),
            'sections' => $form->sections
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
