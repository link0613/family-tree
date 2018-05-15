<?php

namespace app\models;

/**
 * This is the model class for table "education".
 *
 * @property integer $id
 * @property string $country
 * @property string $city
 * @property string $place
 * @property string $begin
 * @property string $end
 * @property integer $item_id
 *
 * @property Item $item
 */
class Education extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'education';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['begin', 'end'], 'safe'],
            [['item_id'], 'required'],
            [['item_id'], 'integer'],
            [['country', 'city'], 'string', 'max' => 100],
            [['place'], 'string', 'max' => 200],
            [
                ['item_id'],
                'exist',
                'skipOnError' => true,
                'targetClass' => Item::className(),
                'targetAttribute' => ['item_id' => 'id']
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'country' => 'Country',
            'city' => 'City',
            'place' => 'Place',
            'begin' => 'Begin',
            'end' => 'End',
            'item_id' => 'Item ID',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getItem()
    {
        return $this->hasOne(Item::className(), ['id' => 'item_id']);
    }
}
