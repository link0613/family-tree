<?php

namespace app\modules\admin\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\TrApplicant;

/**
 * TrApplicantSearch represents the model behind the search form about `app\models\TrApplicant`.
 */
class TrApplicantSearch extends TrApplicant
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'profession_id'], 'integer'],
            [['name', 'contact_address', 'contact_phone', 'contact_fax', 'contact_email', 'father', 'mother', 'grandfather', 'grandmother', 'great_grandfather', 'great_grandmother'], 'safe'],
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
        $query = TrApplicant::find();

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
            'profession_id' => $this->profession_id,
        ]);

        $query->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'contact_address', $this->contact_address])
            ->andFilterWhere(['like', 'contact_phone', $this->contact_phone])
            ->andFilterWhere(['like', 'contact_fax', $this->contact_fax])
            ->andFilterWhere(['like', 'contact_email', $this->contact_email])
            ->andFilterWhere(['like', 'father', $this->father])
            ->andFilterWhere(['like', 'mother', $this->mother])
            ->andFilterWhere(['like', 'grandfather', $this->grandfather])
            ->andFilterWhere(['like', 'grandmother', $this->grandmother])
            ->andFilterWhere(['like', 'great_grandfather', $this->great_grandfather])
            ->andFilterWhere(['like', 'great_grandmother', $this->great_grandmother]);

        return $dataProvider;
    }
}
