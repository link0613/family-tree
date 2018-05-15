<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Item;

/**
 * ItemSearch represents the model behind the search form about `app\models\Item`.
 */
class ItemSearchAdmin extends Item
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'gender', 'gotra_id', 'user_id', 'root', 'death_id', 'father_id', 'mother_id'], 'integer'],
            [['first_name', 'last_name', 'maiden_name', 'about', 'b_country', 'b_city', 'b_date'], 'safe'],
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
        $query = Item::find()->where(['user_id' => Yii::$app->user->id]);

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

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'gender' => $this->gender,
            'gotra_id' => $this->gotra_id,
            'user_id' => $this->user_id,
            'root' => $this->root,
            'b_date' => $this->b_date,
            'death_id' => $this->death_id,
            'father_id' => $this->father_id,
            'mother_id' => $this->mother_id,
        ]);

        $query->andFilterWhere(['like', 'first_name', $this->first_name])
            ->andFilterWhere(['like', 'last_name', $this->last_name])
            ->andFilterWhere(['like', 'maiden_name', $this->maiden_name])
            ->andFilterWhere(['like', 'about', $this->about])
            ->andFilterWhere(['like', 'b_country', $this->b_country])
            ->andFilterWhere(['like', 'b_city', $this->b_city]);

        return $dataProvider;
    }
}
