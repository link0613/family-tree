<?php

namespace app\modules\admin\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\TrAncestor;

/**
 * TrAncestorSearch represents the model behind the search form about `app\models\TrAncestor`.
 */
class TrAncestorSearch extends TrAncestor
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'applicant_id', 'migrate_year', 'migrate_age', 'caste_name_id', 'profession_id', 'state_id', 'district_id', 'district_sub_id', 'city_id'], 'integer'],
            [['name', 'police_station', 'post_office', 'cities_neighbor'], 'safe'],
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
        $query = TrAncestor::find();

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
            'applicant_id' => $this->applicant_id,
            'migrate_year' => $this->migrate_year,
            'migrate_age' => $this->migrate_age,
            'caste_name_id' => $this->caste_name_id,
            'profession_id' => $this->profession_id,
            'state_id' => $this->state_id,
            'district_id' => $this->district_id,
            'district_sub_id' => $this->district_sub_id,
            'city_id' => $this->city_id,
        ]);

        $query->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'police_station', $this->police_station])
            ->andFilterWhere(['like', 'post_office', $this->post_office])
            ->andFilterWhere(['like', 'cities_neighbor', $this->cities_neighbor]);

        return $dataProvider;
    }
}
