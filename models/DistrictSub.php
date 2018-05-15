<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "district_sub".
 *
 * @property string $id
 * @property string $name
 * @property string $district_id
 *
 * @property City[] $cities
 * @property District $district
 * @property TrAncestor[] $trAncestors
 */
class DistrictSub extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'district_sub';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'district_id'], 'required'],
            [['district_id'], 'integer'],
            [['name'], 'string', 'max' => 255],
            [['district_id', 'name'], 'unique', 'targetAttribute' => ['district_id', 'name'], 'message' => 'The combination of Name and District ID has already been taken.'],
            [['district_id'], 'exist', 'skipOnError' => true, 'targetClass' => District::className(), 'targetAttribute' => ['district_id' => 'id']],
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
            'district_id' => 'District ID',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCities()
    {
        return $this->hasMany(City::className(), ['district_sub_id' => 'id'])->inverseOf('districtSub');
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDistrict()
    {
        return $this->hasOne(District::className(), ['id' => 'district_id'])->inverseOf('districtSubs');
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTrAncestors()
    {
        return $this->hasMany(TrAncestor::className(), ['district_sub_id' => 'id'])->inverseOf('districtSub');
    }

    static function juiAutoCompleteMap($q) {
        $v = trim(filter_var(trim($q, FILTER_SANITIZE_STRING)));
        if ( !$v )
            return [];

        $aModels = self::find()
            ->innerJoinWith(['district' => function(\yii\db\ActiveQuery $query) use ($v) {
                $query->select(false)->andOnCondition(['district.name' => $v]);
            }], false)
            ->orderBy('district_sub.name')
            ->indexBy('name')
            ->all();
        return array_keys($aModels);
    }
}
