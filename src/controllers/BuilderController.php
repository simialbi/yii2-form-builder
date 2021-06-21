<?php
/**
 * @package yii2-form-builder
 * @author Simon Karlen <simi.albi@gmail.com>
 */

namespace simialbi\yii2\formbuilder\controllers;

use simialbi\yii2\formbuilder\models\Form;
use simialbi\yii2\formbuilder\models\SearchForm;
use simialbi\yii2\formbuilder\Module;
use Yii;
use yii\web\Controller;

/**
 * Class BuilderController
 * @package simialbi\yii2\formbuilder\controllers
 */
class BuilderController extends Controller
{
    /**
     * {@inheritDoc}
     */
    public function behaviors()
    {
        return parent::behaviors();
    }

    /**
     * Index action
     *
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new SearchForm();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'layouts' => Module::getFormLayouts()
        ]);
    }

    /**
     * Create action
     *
     * @return string
     */
    public function actionCreate()
    {
        $model = new Form();

        return $this->render('create', [
            'model' => $model
        ]);
    }
}
