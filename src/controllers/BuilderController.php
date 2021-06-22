<?php
/**
 * @package yii2-form-builder
 * @author Simon Karlen <simi.albi@gmail.com>
 */

namespace simialbi\yii2\formbuilder\controllers;

use simialbi\yii2\formbuilder\models\Field;
use simialbi\yii2\formbuilder\models\Form;
use simialbi\yii2\formbuilder\models\SearchForm;
use simialbi\yii2\formbuilder\models\Section;
use simialbi\yii2\formbuilder\Module;
use Yii;
use yii\web\Controller;

/**
 * Class BuilderController
 * @package simialbi\yii2\formbuilder\controllers
 *
 * @property-read Module $module
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
    public function actionIndex(): string
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
    public function actionCreate(): string
    {
        $model = new Form();
        $sections = [new Section()];
        $sections[0]->order = 0;
        $sections[0]->default_number_of_cols = 2;

        return $this->render('create', [
            'model' => $model,
            'sections' => $sections,
            'languages' => $this->module->languages,
            'layouts' => Module::getFormLayouts()
        ]);
    }

    /**
     * Add a section
     *
     * @param integer $counter The current counter
     *
     * @return string
     */
    public function actionAddSection(int $counter = 0): string
    {
        $model = new Section();
        $model->order = $counter;
        $model->default_number_of_cols = 2;

        return $this->renderAjax('add-section', [
            'model' => $model,
            'counter' => $counter
        ]);
    }

    /**
     * Add a field
     *
     * @param int $sectionCounter The section counter
     * @param int $counter The current field counter
     *
     * @return string
     */
    public function actionAddField(int $sectionCounter = 0, int $counter = 0): string
    {
        $model = new Field();
        $model->order = $counter;

        return $this->renderAjax('add-field', [
            'model' => $model,
            'sectionCounter' => $sectionCounter,
            'counter' => $counter,
            'fieldTypes' => Module::getFieldTypes(),
            'dependencyOperators' => Module::getDependencyOperators(),
            'relationClasses' => $this->module->relationClasses
        ]);
    }
}
