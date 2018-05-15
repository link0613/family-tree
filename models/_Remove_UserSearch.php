<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * ItemSearch represents the model behind the search form about `app\models\Item`.
 */
class UserSearch extends Item
{
    /**
     * @inheritdoc
     */
    public $gotra;

    public function rules()
    {
        return [
            [
                [
                    'first_name',
                    'last_name',
                    'gotra',
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
    public function search($params)
    {
        $query = Item::find();
        $query->joinWith('gotra');

        // add conditions that should always apply here
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'gender' => $this->gender,
            'user_id' => $this->user_id,
            'root' => $this->root,
            'b_date' => $this->b_date,
            'death_id' => $this->death_id,
            'father_id' => $this->father_id,
            'mother_id' => $this->mother_id
        ]);

        $query->andFilterWhere(['like', 'first_name', $this->first_name])
            ->andFilterWhere(['like', 'gotra.name', $this->gotra])
            ->andFilterWhere(['like', 'last_name', $this->last_name]);

        return $dataProvider;
    }
}
