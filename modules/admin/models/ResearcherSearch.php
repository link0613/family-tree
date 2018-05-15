<?php

namespace app\modules\admin\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Researcher;

/**
 * ResearcherSearch represents the model behind the search form about `app\models\Researcher`.
 */
class ResearcherSearch extends Researcher
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'status', 'state_id', 'district_id', 'district_sub_id', 'city_id', 'service'], 'integer'],
            [['name', 'email', 'contact_phone', 'contact_skype', 'contact_whats_app', 'custom_location', 'email_paypal', 'linked_in', 'business', 'website', 'extra_1', 'extra_2', 'extra_3', 'extra_4', 'extra_5', 'extra_6', 'extra_7', 'extra_8', 'extra_9', 'extra_10'], 'safe'],
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
        $query = Researcher::find();

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
            'status' => $this->status,
            'state_id' => $this->state_id,
            'district_id' => $this->district_id,
            'district_sub_id' => $this->district_sub_id,
            'city_id' => $this->city_id,
            'service' => $this->service,
        ]);

        $query->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'email', $this->email])
            ->andFilterWhere(['like', 'contact_phone', $this->contact_phone])
            ->andFilterWhere(['like', 'contact_skype', $this->contact_skype])
            ->andFilterWhere(['like', 'contact_whats_app', $this->contact_whats_app])
            ->andFilterWhere(['like', 'custom_location', $this->custom_location])
            ->andFilterWhere(['like', 'email_paypal', $this->email_paypal])
            ->andFilterWhere(['like', 'linked_in', $this->linked_in])
            ->andFilterWhere(['like', 'business', $this->business])
            ->andFilterWhere(['like', 'website', $this->website])
            ->andFilterWhere(['like', 'extra_1', $this->extra_1])
            ->andFilterWhere(['like', 'extra_2', $this->extra_2])
            ->andFilterWhere(['like', 'extra_3', $this->extra_3])
            ->andFilterWhere(['like', 'extra_4', $this->extra_4])
            ->andFilterWhere(['like', 'extra_5', $this->extra_5])
            ->andFilterWhere(['like', 'extra_6', $this->extra_6])
            ->andFilterWhere(['like', 'extra_7', $this->extra_7])
            ->andFilterWhere(['like', 'extra_8', $this->extra_8])
            ->andFilterWhere(['like', 'extra_9', $this->extra_9])
            ->andFilterWhere(['like', 'extra_10', $this->extra_10]);

        return $dataProvider;
    }
}
