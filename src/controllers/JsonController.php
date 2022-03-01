<?php
/**
 * @package yii2-form-builder
 * @author Simon Karlen <simi.albi@gmail.com>
 */

namespace simialbi\yii2\formbuilder\controllers;

use simialbi\yii2\formbuilder\models\Form;
use Yii;
use yii\filters\ContentNegotiator;
use yii\filters\Cors;
use yii\web\Controller;
use yii\web\Response;

/**
 * @property-read \simialbi\yii2\formbuilder\Module $module
 */
class JsonController extends Controller
{
    /**
     * {@inheritDoc}
     */
    public function behaviors(): array
    {
        return [
            'contentNegotiator' => [
                'class' => ContentNegotiator::class,
                'formats' => [
                    'application/json' => Response::FORMAT_JSON
                ]
            ],
            'corsFilter' => [
                'class' => Cors::class,
                'cors' => [
                    'Origin' => ['https://' . $this->request->hostName],
                    'Access-Control-Request-Method' => ['GET', 'POST', 'PUT', 'PATCH', 'DELETE', 'HEAD', 'OPTIONS'],
                    'Access-Control-Request-Headers' => ['*'],
                    'Access-Control-Allow-Credentials' => null,
                    'Access-Control-Max-Age' => 86400
                ]
            ]
        ];
    }

    /**
     * @return array
     */
    public function actionModels(): array
    {
        $enum = [];
        $enumTitles = [];
        foreach ($this->module->relationClasses as $class => $details) {
            $enum[] = $class;
            $enumTitles[] = $details['name'];
        }

        return [
            'title' => Yii::t('simialbi/formbuilder/json', 'Model'),
            'type' => 'string',
            'enum' => $enum,
            'options' => [
                'inputAttributes' => ['onchange' => 'window.sa.formBuilder.changeSaveActionModel.apply(this);'],
                'enum_titles' => $enumTitles
            ],
            'default' => $enum[0]
        ];
    }

    /**
     *
     * @param int $model
     * @return array
     */
    public function actionFields(int $model = 0): array
    {
        $referer = $this->request->headers->get('Referer');
        $url = parse_url($referer);
        parse_str($url['query'], $query);
        $form = Form::findOne($query['id']);
        $attributes = [];
        $values = array_values($this->module->relationClasses);
        foreach ($form->fields as $field) {
            $attributes[$field->name] = [
                'title' => $field->label,
                'type' => 'string',
                'enum' => array_keys($values[$model]['attributes']),
                'options' => [
                    'enum_titles' => array_values($values[$model]['attributes'])
                ]
            ];
        }

        return [
            'type' => 'object',
            'format' => 'table',
            'properties' => $attributes
        ];
    }
}
