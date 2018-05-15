<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "caste_name".
 *
 * @property string $id
 * @property string $name
 *
 * @property TrAncestor[] $trAncestors
 */
class CasteName extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'caste_name';
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
        return $this->hasMany(TrAncestor::className(), ['caste_name_id' => 'id'])->inverseOf('casteName');
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
