<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "state".
 *
 * @property string $id
 * @property string $name
 *
 * @property District[] $districts
 * @property TrAncestor[] $trAncestors
 */
class State extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'state';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name'], 'required'],
            [['name'], 'string', 'max' => 255],
            [['name'], 'unique'],
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
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDistricts()
    {
        return $this->hasMany(District::className(), ['state_id' => 'id'])->inverseOf('state');
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTrAncestors()
    {
        return $this->hasMany(TrAncestor::className(), ['state_id' => 'id'])->inverseOf('state');
    }

    static function juiAutoCompleteMap() {
        static $res = null;
        if ( $res === null ) {
            $aModels = self::find()->orderBy('name')->indexBy('name')->all();
            $res = array_keys($aModels);
        }
        return $res;
    }
}
