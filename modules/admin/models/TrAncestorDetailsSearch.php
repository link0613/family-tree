<?php

namespace app\modules\admin\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\TrAncestorDetails;

/**
 * TrAncestorDetailsSearch represents the model behind the search form about `app\models\TrAncestorDetails`.
 */
class TrAncestorDetailsSearch extends TrAncestorDetails
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id'], 'integer'],
            [['property', 'family_members', 'correspondence', 'other'], 'safe'],
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
        $query = TrAncestorDetails::find();

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
        ]);

        $query->andFilterWhere(['like', 'property', $this->property])
            ->andFilterWhere(['like', 'family_members', $this->family_members])
            ->andFilterWhere(['like', 'correspondence', $this->correspondence])
            ->andFilterWhere(['like', 'other', $this->other]);

        return $dataProvider;
    }
}
