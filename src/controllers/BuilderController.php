<?php
/**
 * @package yii2-form-builder
 * @author Simon Karlen <simi.albi@gmail.com>
 */

namespace simialbi\yii2\formbuilder\controllers;

use cebe\markdown\MarkdownExtra;
use simialbi\yii2\formbuilder\models\Field;
use simialbi\yii2\formbuilder\models\Form;
use simialbi\yii2\formbuilder\models\SearchForm;
use simialbi\yii2\formbuilder\models\Section;
use simialbi\yii2\formbuilder\models\Validator;
use simialbi\yii2\formbuilder\Module;
use Yii;
use yii\helpers\Inflector;
use yii\helpers\Json;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

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
     * @return string|\yii\web\Response
     * @throws \yii\db\Exception
     */
    public function actionCreate()
    {
        $model = new Form();
        $sections = [new Section()];
        $sections[0]->order = 0;
        $sections[0]->default_number_of_cols = 2;

        if ($model->load(Yii::$app->request->post())) {
            $saved = $this->saveForm($model);

            return $this->redirect(['index']);
        }

        return $this->render('create', [
            'model' => $model,
            'sections' => $sections,
            'languages' => $this->module->languages,
            'layouts' => Module::getFormLayouts(),
            'fieldTypes' => Module::getFieldTypes(),
            'relationClasses' => $this->module->relationClasses,
            'validators' => $this->module->validators,
            'validatorOptions' => $this->getValidatorOptions()
        ]);
    }

    /**
     * Update an existing form
     *
     * @param integer $id The forms primary key
     *
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException|\yii\db\Exception
     */
    public function actionUpdate(int $id)
    {
        $model = $this->findForm($id);

        if ($model->load(Yii::$app->request->post())) {
            $this->saveForm($model);

            return $this->redirect(['index']);
        }

        return $this->render('update', [
            'model' => $model,
            'sections' => $model->sections,
            'languages' => $this->module->languages,
            'layouts' => Module::getFormLayouts(),
            'fieldTypes' => Module::getFieldTypes(),
            'relationClasses' => $this->module->relationClasses,
            'validators' => $this->module->validators,
            'validatorOptions' => $this->getValidatorOptions()
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
            'counter' => $counter,
            'fieldTypes' => Module::getFieldTypes(),
            'relationClasses' => $this->module->relationClasses,
            'validators' => $this->module->validators,
            'validatorOptions' => $this->getValidatorOptions()
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
            'relationClasses' => $this->module->relationClasses,
            'validators' => $this->module->validators,
            'validatorOptions' => $this->getValidatorOptions()
        ]);
    }

    /**
     * Add a validator
     *
     * @param int $sectionCounter The section counter
     * @param int $fieldCounter The field counter
     * @param int $counter The current validator counter
     *
     * @return string
     */
    public function actionAddValidator(int $sectionCounter = 0, int $fieldCounter = 0, int $counter = 0): string
    {
        $model = new Validator();

        return $this->renderAjax('add-validator', [
            'model' => $model,
            'sectionCounter' => $sectionCounter,
            'fieldCounter' => $fieldCounter,
            'counter' => $counter,
            'validators' => $this->module->validators,
            'validatorOptions' => $this->getValidatorOptions()
        ]);
    }

    /**
     * Show form
     *
     * @param integer $id
     *
     * @return \yii\web\Response
     */
    public function actionForm(int $id): \yii\web\Response
    {
        return $this->redirect(['render/form', 'id' => $id]);
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

    /**
     * Get validator options
     *
     * @return array
     */
    protected function getValidatorOptions(): array
    {
        return Yii::$app->cache->getOrSet('sa-formbuilder-validators', function () {
            $properties = [];
            $markdown = new MarkdownExtra();
            $markdown->html5 = true;
            foreach ($this->module->validators as $validator => $name) {
                $r = new \ReflectionClass($validator);
                $props = $r->getProperties(\ReflectionProperty::IS_PUBLIC);
                $properties[$validator] = [
                    'title' => $name,
                    'type' => 'object',
                    'properties' => []
                ];
                foreach ($props as $property) {
                    $config = [
                        'title' => Inflector::camel2words($property->name),
                        'type' => 'string',
                        'description' => ''
                    ];
                    $comment = strtr(trim(preg_replace('/^\s*\**([ \t])?/m', '', trim($property->getDocComment(), '/'))), "\r", '');
                    if (preg_match('/^\s*@\w+ ([\w|\\\\]+) (.+)/s', $comment, $matches)) {
                        $comment = $matches[2];
                        if (preg_match('/(string|int(?:eger)?|bool(?:ean|float|double)?)/', $matches[1], $matches)) { // TODO |array
                            switch ($matches[1]) {
                                case 'int':
                                case 'integer':
                                    $config['type'] = 'integer';
                                    break;
                                case 'bool':
                                case 'boolean':
                                    $config['type'] = 'boolean';
                                    $config['format'] = 'checkbox';
                                    break;
                                case 'float':
                                case 'double':
                                    $config['type'] = 'number';
                                    break;
                                case 'array':
                                    $config['items'] = ['type' => 'string'];
                                case 'string':
                                    $config['type'] = $matches[1];
                                    break;
                            }
                        }
                    }
                    if ($comment !== '') {
                        $config['options']['infoText'] = $markdown->parse($comment);
                    }
                    $properties[$validator]['properties'][$property->name] = $config;
                }
            }

            return $properties;
        }, 86400);
    }

    /**
     * Save form
     * @param Form $model
     * @return boolean `true` if save operation was successful otherwise `false`
     * @throws \yii\db\Exception
     */
    protected function saveForm(Form $model): bool
    {
        $sections = Yii::$app->request->post('Section', []);
        $fields = Yii::$app->request->post('Field', []);
        $validators = Yii::$app->request->post('Validator', []);

        $transaction = Yii::$app->db->beginTransaction();
        if ($model->save()) {
            for ($i = 0; $i < count($sections); $i++) {
                $section = (!empty($sections[$i]['id'])) ? Section::findOne($sections[$i]['id']) : new Section();
                if ($section->load($sections[$i], '')) {
                    $section->form_id = $model->id;
                    if ($section->save()) {
                        for ($k = 0; $k < count($fields[$i]); $k++) {
                            $field = (!empty($fields[$i][$k]['id'])) ? Field::findOne($fields[$i][$k]['id']) : new Field();
                            if ($field->load($fields[$i][$k], '')) {
                                $field->section_id = $section->id;
                                if ($field->save()) {
                                    for ($l = 0; $l < count($validators[$i][$k]); $l++) {
                                        $validator = (!empty($validators[$i][$k][$l]['id']))
                                            ? Validator::findOne($validators[$i][$k][$l]['id'])
                                            : new Validator();
                                        if ($validator->load($validators[$i][$k][$l], '')) {
                                            $validator->field_id = $field->id;
                                            if (is_array($validator->configuration)) {
                                                $config = $validator->configuration;
                                                foreach ($config as $key => $value) {
                                                    if ($value === '') {
                                                        unset($config[$key]);
                                                    } elseif ($value === 'off') {
                                                        $config[$key] = false;
                                                    } elseif ($value === 'on') {
                                                        $config[$key] = true;
                                                    }
                                                }
                                                $validator->configuration = Json::encode($config);
                                            }
                                            if (!$validator->save()) {
                                                $transaction->rollBack();
                                                var_dump($validator->errors);
                                                exit;
                                            }
                                        }
                                    }
                                } else {
                                    $transaction->rollBack();
                                    var_dump($field->errors);
                                    exit;
                                }
                            }
                        }
                    } else {
                        $transaction->rollBack();
                        var_dump($section->errors);
                        exit;
                    }
                }
            }
        } else {
            $transaction->rollBack();
            var_dump($model->errors);
            exit;
        }

        $transaction->commit();

        return true;
    }
}
