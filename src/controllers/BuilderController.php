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
            'relationClasses' => $this->module->relationClasses
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

        $validatorOptions = Yii::$app->cache->getOrSet('sa-formbuilder-validators', function () {
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
                        if (preg_match('/(string|int(?:eger)?|array|bool(?:ean|float|double)?)/', $matches[1], $matches)) {
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

        return $this->renderAjax('add-validator', [
            'model' => $model,
            'sectionCounter' => $sectionCounter,
            'fieldCounter' => $fieldCounter,
            'counter' => $counter,
            'validators' => $this->module->validators,
            'validatorOptions' => $validatorOptions
        ]);
    }
}
