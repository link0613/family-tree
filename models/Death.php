<?php

namespace app\models;

use \yii\db\ActiveRecord;

/**
 * This is the model class for table "death".
 *
 * @property integer $id
 * @property integer $item_id
 * @property string $country
 * @property string $city
 * @property string $date
 * @property string $reason
 * @property string $bury_country
 * @property string $bury_city
 * @property string $cemetery
 * @property string $bury_date
 *
 * @property Item $item
 */
class Death extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'death';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['date', 'bury_date'], 'safe'],
            [['country', 'city', 'bury_country', 'bury_city'], 'string', 'max' => 100],
            [['reason', 'cemetery'], 'string', 'max' => 200],
            [
                ['item_id'],
                'exist',
                'skipOnError' => false,
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
            'item_id' => 'Item id',
            'country' => 'Death country',
            'city' => 'Death city',
            'date' => 'Death date',
            'reason' => 'Death reason',
            'bury_country' => 'Bury country',
            'bury_city' => 'Bury city',
            'cemetery' => 'Cemetery',
            'bury_date' => 'Bury date'
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getItem()
    {
        return $this->hasOne(Item::className(), ['id' => 'item_id']);
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return int
     */
    public function getItemId()
    {
        return $this->item_id;
    }

    /**
     * @param int $itemId
     * @return Death
     */
    public function setItemId($itemId)
    {
        $this->item_id = $itemId;
        return $this;
    }

    /**
     * @return string
     */
    public function getCoutry()
    {
        return $this->country;
    }

    /**
     * @param string $country
     * @return Death
     */
    public function setCountry($country)
    {
        $this->country = $country;
        return $this;
    }

    /**
     * @return string
     */
    public function getCity()
    {
        return $this->city;
    }

    /**
     * @param string $city
     * @return Death
     */
    public function setCity($city)
    {
        $this->city = $city;
        return $this;
    }

    /**
     * @return string
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * @param string $date
     * @return Death
     */
    public function setDate($date)
    {
        $this->date = $date;
        return $this;
    }

    /**
     * @return string
     */
    public function getReason()
    {
        return $this->reason;
    }

    /**
     * @param string $reason
     * @return Death
     */
    public function setReason($reason)
    {
        $this->reason = $reason;
        return $this;
    }

    /**
     * @return string
     */
    public function getBuryCountry()
    {
        return $this->bury_country;
    }

    /**
     * @param string $buryCountry
     * @return Death
     */
    public function setBuryCountry($buryCountry)
    {
        $this->bury_country = $buryCountry;
        return $this;
    }

    /**
     * @return string
     */
    public function getBuryCity()
    {
        return $this->bury_city;
    }

    /**
     * @param string $buryCity
     * @return Death
     */
    public function setBuryCity($buryCity)
    {
        $this->bury_city = $buryCity;
        return $this;
    }

    /**
     * @return string
     */
    public function getCemetery()
    {
        return $this->cemetery;
    }

    /**
     * @param string $cemetery
     * @return Death
     */
    public function setCemetery($cemetery)
    {
        $this->cemetery = $cemetery;
        return $this;
    }

    /**
     * @return string
     */
    public function getBuryDate()
    {
        return $this->bury_date;
    }

    /**
     * @param string $buryDate
     * @return Death
     */
    public function setBuryDate($buryDate)
    {
        $this->bury_date = $buryDate;
        return $this;
    }
}
