<?php

namespace app\models;

/**
 * This is the model class for table "gotra".
 *
 * @property integer $id
 * @property string $name
 *
 * @property Item[] $items
 */
class Gotra extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'gotra';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name'], 'required'],
            [['name'], 'string', 'max' => 100],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Gotra',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getItems()
    {
        return $this->hasMany(Item::className(), ['gotra_id' => 'id']);
    }

    public function getId()
    {
        return $this->id;
    }
}
