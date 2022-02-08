<?php
/**
 * @package yii2-form-builder
 * @author Simon Karlen <simi.albi@gmail.com>
 */

namespace simialbi\yii2\formbuilder\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use yii\db\Expression;

/**
 * Class SearchForm
 * @package simialbi\yii2\formbuilder\models
 */
class SearchForm extends Form
{
    /**
     * {@inheritDoc}
     */
    public function rules(): array
    {
        return [
            ['id', 'integer'],
            ['name', 'string', 'max' => 255],
            [
                'layout',
                'in',
                'range' => [
                    static::LAYOUT_DEFAULT,
                    static::LAYOUT_FLOATING_LABEL,
                    static::LAYOUT_PLACEHOLDER,
                    static::LAYOUT_HORIZONTAL
                ]
            ],
            ['language', 'string', 'min' => 2, 'max' => 5]
        ];
    }

    /**
     * {@inheritDoc}
     */
    public function scenarios(): array
    {
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search(array $params): ActiveDataProvider
    {
        $query = Form::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query
        ]);

        $this->load($params);

        if (!$this->validate()) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'layout' => $this->layout,
            'created_by' => $this->created_by,
            'updated_by' => $this->updated_by
        ]);
        $query->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'language', $this->language])
            ->andFilterWhere(['=', new Expression('FROM_UNIXTIME([[created_at]], \'%d.%m.%Y\')'), $this->created_at])
            ->andFilterWhere(['=', new Expression('FROM_UNIXTIME([[updated_at]], \'%d.%m.%Y\')'), $this->updated_at]);

        return $dataProvider;
    }
}
