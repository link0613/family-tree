<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "profession".
 *
 * @property string $id
 * @property string $name
 *
 * @property TrAncestor[] $trAncestors
 * @property TrApplicant[] $trApplicants
 */
class Profession extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'profession';
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
    public function getTrAncestors()
    {
        return $this->hasMany(TrAncestor::className(), ['profession_id' => 'id'])->inverseOf('profession');
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTrApplicants()
    {
        return $this->hasMany(TrApplicant::className(), ['profession_id' => 'id'])->inverseOf('profession');
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
