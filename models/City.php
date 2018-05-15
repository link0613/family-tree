<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "city".
 *
 * @property string $id
 * @property string $name
 * @property string $district_sub_id
 *
 * @property DistrictSub $districtSub
 * @property TrAncestor[] $trAncestors
 */
class City extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'city';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'district_sub_id'], 'required'],
            [['district_sub_id'], 'integer'],
            [['name'], 'string', 'max' => 255],
            [['district_sub_id', 'name'], 'unique', 'targetAttribute' => ['district_sub_id', 'name'], 'message' => 'The combination of Name and District Sub ID has already been taken.'],
            [['district_sub_id'], 'exist', 'skipOnError' => true, 'targetClass' => DistrictSub::className(), 'targetAttribute' => ['district_sub_id' => 'id']],
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
            'district_sub_id' => 'District Sub ID',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDistrictSub()
    {
        return $this->hasOne(DistrictSub::className(), ['id' => 'district_sub_id'])->inverseOf('cities');
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTrAncestors()
    {
        return $this->hasMany(TrAncestor::className(), ['city_id' => 'id'])->inverseOf('city');
    }

    static function juiAutoCompleteMap($q) {
        $v = trim(filter_var(trim($q, FILTER_SANITIZE_STRING)));
        if ( !$v )
            return [];

        $aModels = self::find()
            ->innerJoinWith(['districtSub' => function(\yii\db\ActiveQuery $query) use ($v) {
                $query->select(false)->andOnCondition(['district_sub.name' => $v]);
            }], false)
            ->orderBy('city.name')
            ->indexBy('name')
            ->all();
        return array_keys($aModels);
    }
}
