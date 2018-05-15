<?php

namespace app\modules\admin\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\TrAncestorMigration;

/**
 * TrAncestorMigrationSearch represents the model behind the search form about `app\models\TrAncestorMigration`.
 */
class TrAncestorMigrationSearch extends TrAncestorMigration
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id'], 'integer'],
            [['ship', 'agent', 'port_embarkation', 'date_embarkation', 'port_disembarkation', 'date_disembarkation'], 'safe'],
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
        $query = TrAncestorMigration::find();

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
            'date_embarkation' => $this->date_embarkation,
            'date_disembarkation' => $this->date_disembarkation,
        ]);

        $query->andFilterWhere(['like', 'ship', $this->ship])
            ->andFilterWhere(['like', 'agent', $this->agent])
            ->andFilterWhere(['like', 'port_embarkation', $this->port_embarkation])
            ->andFilterWhere(['like', 'port_disembarkation', $this->port_disembarkation]);

        return $dataProvider;
    }
}
