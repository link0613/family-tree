<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "district".
 *
 * @property string $id
 * @property string $name
 * @property string $state_id
 *
 * @property State $state
 * @property DistrictSub[] $districtSubs
 * @property TrAncestor[] $trAncestors
 */
class District extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'district';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'state_id'], 'required'],
            [['state_id'], 'integer'],
            [['name'], 'string', 'max' => 255],
            [['state_id', 'name'], 'unique', 'targetAttribute' => ['state_id', 'name'], 'message' => 'The combination of Name and State ID has already been taken.'],
            [['state_id'], 'exist', 'skipOnError' => true, 'targetClass' => State::className(), 'targetAttribute' => ['state_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
            'state_id' => 'State ID',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getState()
    {
        return $this->hasOne(State::className(), ['id' => 'state_id'])->inverseOf('districts');
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDistrictSubs()
    {
        return $this->hasMany(DistrictSub::className(), ['district_id' => 'id'])->inverseOf('district');
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTrAncestors()
    {
        return $this->hasMany(TrAncestor::className(), ['district_id' => 'id'])->inverseOf('district');
    }

    static function juiAutoCompleteMap($q) {
        $v = trim(filter_var(trim($q, FILTER_SANITIZE_STRING)));
        if ( !$v )
            return [];

        $aModels = self::find()
            ->innerJoinWith(['state' => function(\yii\db\ActiveQuery $query) use ($v) {
                $query->select(false)->andOnCondition(['state.name' => $v]);
            }], false)
            ->orderBy('district.name')
            ->indexBy('name')
            ->all();
        return array_keys($aModels);
    }
}
