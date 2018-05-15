<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "location".
 *
 * @property integer $id
 * @property string $country
 * @property string $city
 * @property string $phone
 * @property string $post_code
 * @property integer $item_id
 *
 * @property Item $item
 */
class Location extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'location';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['item_id'], 'required'],
            [['item_id'], 'integer'],
            [['country', 'city'], 'string', 'max' => 100],
            [['phone'], 'string', 'max' => 50],
            [['post_code'], 'string', 'max' => 10],
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
            'phone' => 'Phone',
            'post_code' => 'Post Code',
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
