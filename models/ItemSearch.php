<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * ItemSearch represents the model behind the search form about `app\models\Item`.
 */
class ItemSearch extends Item
{
    public $father;
    public $mother;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [
                [
                    'first_name',
                    'last_name',
                    'b_date',
                    'b_city',
                    'gotra_id',
                    'occupation',
                    'father',
                    'mother',
                ],
                'safe'
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search(array $params = [])
    {
        $query = Item::find();

        // add conditions that should always apply here
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $dataProvider->setSort([
            'attributes' => [
                'id',
                'first_name',
                'middle_name',
                'last_name',
                'maiden_name'
            ]
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'gender' => $this->gender,
            'gotra_id' => $this->gotra_id,
            'privacy' => $this->privacy,
            'father_id' => $this->father_id,
            'mother_id' => $this->mother_id,
            'occupation' => $this->occupation
        ]);

        $query->andFilterWhere(['like', 'first_name', $this->first_name])
            ->andFilterWhere(['like', 'middle_name', $this->middle_name])
            ->andFilterWhere(['like', 'last_name', $this->last_name])
            ->andFilterWhere(['like', 'maiden_name', $this->maiden_name])
            ->andFilterWhere(['like', 'b_country', $this->b_country])
            ->andFilterWhere(['like', 'b_city', $this->b_city])
            ->andFilterWhere(['like', 'b_date', $this->b_date]);

        return $dataProvider;
    }
}
