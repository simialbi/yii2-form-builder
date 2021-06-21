<?php

use kartik\date\DatePicker;
use kartik\grid\GridView;
use kartik\select2\Select2;
use rmrevin\yii\fontawesome\FAS;
use yii\bootstrap4\Html;
use yii\helpers\ArrayHelper;

/** @var $this \yii\web\View */
/** @var $searchModel \simialbi\yii2\formbuilder\models\SearchForm */
/** @var $dataProvider \yii\data\ActiveDataProvider */
/** @var $layouts array */

$this->title = Yii::t('simialbi/formbuilder', 'My Forms');
$this->params['breadcrumbs'] = [$this->title];
?>

<div class="sa-formbuilder-builder-index">
    <?= GridView::widget([
        'bsVersion' => 4,
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'pjax' => true,
        'export' => false,
        'bordered' => false,
        'panel' => [
            'heading' => $this->title,
            'headingOptions' => [
                'class' => [
                    'card-header',
                    'd-flex',
                    'align-items-center',
                    'justify-content-between',
                    'bg-white'
                ]
            ],
            'titleOptions' => [
                'class' => ['card-title', 'm-0']
            ],
            'summaryOptions' => [
                'class' => []
            ],
            'beforeOptions' => [
                'class' => [
                    'card-body',
                    'py-2',
                    'border-bottom',
                    'd-flex',
                    'justify-content-between',
                    'align-items-center'
                ]
            ],
            'footerOptions' => [
                'class' => ['card-footer', 'bg-white']
            ],
            'options' => [
                'class' => ['card']
            ]
        ],
        'panelTemplate' => '
            {panelHeading}
            {panelBefore}
            {items}
            {panelFooter}
        ',
        'panelHeadingTemplate' => '
            {title}
            {toolbar}
        ',
        'panelFooterTemplate' => '{pager}{footer}',
        'panelBeforeTemplate' => '{pager}{summary}',
        'panelAfterTemplate' => '',
        'containerOptions' => [],
        'toolbar' => [
            [
                'content' => Html::a(FAS::i('plus'), ['builder/create'], [
                    'class' => ['btn', 'btn-primary'],
                    'data' => [
                        'pjax' => '0'
                    ]
                ])
            ]
        ],
        'columns' => [
            [
                'class' => 'kartik\grid\SerialColumn',
                'hAlign' => GridView::ALIGN_CENTER,
                'vAlign' => GridView::ALIGN_MIDDLE
            ],
            [
                'class' => 'kartik\grid\DataColumn',
                'attribute' => 'name',
                'vAlign' => GridView::ALIGN_MIDDLE
            ],
            [
                'class' => 'kartik\grid\DataColumn',
                'attribute' => 'layout',
                'value' => function ($model, $key, $index, $column) use ($layouts) {
                    /** @var $column \kartik\grid\DataColumn */
                    return ArrayHelper::getValue($layouts, $model->{$column->attribute});
                },
                'filterType' => GridView::FILTER_SELECT2,
                'filter' => $layouts,
                'filterWidgetOptions' => [
                    'theme' => Select2::THEME_KRAJEE_BS4,
                    'bsVersion' => 4,
                    'options' => ['placeholder' => ''],
                    'pluginOptions' => ['allowClear' => true]
                ]
            ],
            [
                'class' => 'kartik\grid\DataColumn',
                'attribute' => 'created_at',
                'format' => 'datetime:dd.MM.yyyy HH:mm',
                'filterType' => GridView::FILTER_DATE,
                'filterWidgetOptions' => [
                    'type' => DatePicker::TYPE_INPUT
                ]
            ],
            [
                'class' => 'kartik\grid\ActionColumn'
            ]
        ]
    ]); ?>
</div>
