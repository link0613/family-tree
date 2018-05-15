<?php

namespace app\models;

/**
 * This is the model class for table "item".
 *
 * @property integer $id
 * @property integer $user_id
 * @property string $first_name
 * @property string $middle_name
 * @property string $last_name
 * @property string $maiden_name
 * @property integer $gender
 * @property integer $gotra_id
 * @property integer $death_id
 * @property integer $privacy
 * @property integer $root
 * @property integer $father_id
 * @property integer $mother_id
 * @property string $image
 * @property string $email
 * @property string $about
 * @property string $occupation
 * @property string $b_country
 * @property string $b_city
 * @property string $b_date
 * @property integer $node_type
 * @property integer $married
 *
 * @property Item $father
 * @property Item $mother
 * @property Death $death
 * @property Education[] $educations
 * @property User $user
 * @property Gotra $gotra
 * @property Item[] $itemsSameUser
 * @property Location[] $locations
 */
class Item extends \yii\db\ActiveRecord
{
    /*
     * Node type constants
     */
    CONST NODE_TYPE_MOTHER = 1;
    CONST NODE_TYPE_FATHER = 2;
    CONST NODE_TYPE_SISTER = 3;
    CONST NODE_TYPE_BROTHER = 4;
    CONST NODE_TYPE_DAUGHTER = 5;
    CONST NODE_TYPE_SON = 6;
    CONST NODE_TYPE_SPOUSE_PARTNER = 7;
    CONST NODE_TYPE_MARRIAGE = 8;

    /*
     * Gender constants.
     */
    const GENDER_MALE = 1;
    const GENDER_FEMALE = 0;

    /*
     * Item privacy constants
     */
    const PRIVACY_OPEN = 0;
    const PRIVACY_CLOSED = 1;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'item';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [
                [
                    'gender',
                    'married',
                    'gotra_id',
                    'user_id',
                    'root',
                    'death_id',
                    'privacy',
                    'node_type',
                    'father_id',
                    'mother_id'
                ],
                'integer'
            ],
            [
                [
                    'user_id'
                ],
                'required'
            ],
            [
                [
                    'about'
                ],
                'string'
            ],
            [
                [
                    'b_date'
                ],
                'safe'
            ],
            [
                [
                    'first_name',
                    'occupation',
                    'middle_name',
                    'last_name',
                    'maiden_name',
                    'image',
                    'email',
                    'b_country',
                    'b_city'
                ],
                'string',
                'max' => 100
            ],
            [
                ['user_id'],
                'exist',
                'skipOnError' => true,
                'targetClass' => User::className(),
                'targetAttribute' => ['user_id' => 'id']
            ],
            [
                ['gotra_id'],
                'exist',
                'skipOnError' => true,
                'targetClass' => Gotra::className(),
                'targetAttribute' => ['gotra_id' => 'id']
            ],
            [
                ['father_id'],
                'exist',
                'skipOnError' => true,
                'targetClass' => Item::className(),
                'targetAttribute' => ['father_id' => 'id']
            ],
            [
                ['mother_id'],
                'exist',
                'skipOnError' => true,
                'targetClass' => Item::className(),
                'targetAttribute' => ['mother_id' => 'id']
            ]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'first_name' => 'First Name',
            'middle_name' => 'Middle Name',
            'last_name' => 'Last Name',
            'maiden_name' => 'Maiden Name',
            'gender' => 'Gender',
            'married' => 'Marital Status',
            'gotra_id' => 'Gotra ID',
            'user_id' => 'User ID',
            'image' => 'Image',
            'email' => 'Email',
            'root' => 'Root',
            'about' => 'About',
            'occupation' => 'Occupation',
            'b_country' => 'Birth Country',
            'b_city' => 'Birth City',
            'b_date' => 'Birth Date',
            'death_id' => 'Death ID',
            'privacy' => 'Privacy',
            'node_type' => 'Node Type',
            'father_id' => 'Father ID',
            'mother_id' => 'Mother ID'
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDeath()
    {
        return $this->hasOne(Death::className(), ['item_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getEducations()
    {
        return $this->hasMany(Education::className(), ['item_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getGotra()
    {
        return $this->hasOne(Gotra::className(), ['id' => 'gotra_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getItemsSameUser()
    {
        return $this->hasMany(Item::className(), ['user_id' => 'user_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getFather()
    {
        return $this->hasOne(Item::className(), ['id' => 'father_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getFatherChildren()
    {
        return $this->hasMany(Item::className(), ['father_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMother()
    {
        return $this->hasOne(Item::className(), ['id' => 'mother_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMotherChildren()
    {
        return $this->hasMany(Item::className(), ['mother_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getLocations()
    {
        return $this->hasMany(Location::className(), ['item_id' => 'id']);
    }

    /**
     * @return Item[]
     */
    public function getSiblings()
    {
        $siblingsByFather = $siblingsByMother = [];

        if(!is_null($this->father_id)){
            $siblingsByFather = Item::find()
                ->where(['father_id' => $this->father_id])
                ->andWhere(['<>', 'id', $this->id])
                ->andWhere(['user_id' => $this->user_id])
                ->all();
        }elseif(!is_null($this->mother_id)){
            $siblingsByMother = Item::find()
                ->where(['mother_id' => $this->mother_id])
                ->andWhere(['<>', 'id', $this->id])
                ->andWhere(['user_id' => $this->user_id])
                ->all();
        }

        return array_merge($siblingsByFather, $siblingsByMother);
    }

    /**
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return int
     */
    public function getUserId()
    {
        return $this->user_id;
    }

    /**
     * @param int $userId
     * @return Item
     */
    public function setUserId($userId)
    {
        $this->user_id = $userId;
        return $this;
    }

    /**
     * @return string
     */
    public function getFirstName()
    {
        return $this->first_name;
    }

    /**
     * @param string $firstName
     * @return Item
     */
    public function setFirstName(string $firstName)
    {
        $this->first_name = $firstName;
        return $this;
    }

    /**
     * @return string
     */
    public function getMiddleName()
    {
        return $this->middle_name;
    }

    /**
     * @param string $middleName
     * @return Item
     */
    public function setMiddleName(string $middleName)
    {
        $this->middle_name = $middleName;
        return $this;
    }

    /**
     * @return string
     */
    public function getLastName()
    {
        return $this->last_name;
    }

    /**
     * @param string $lastName
     * @return Item
     */
    public function setLastName(string $lastName)
    {
        $this->last_name = $lastName;
        return $this;
    }

    /**
     * @return string
     */
    public function getMaidenName()
    {
        return $this->maiden_name;
    }

    /**
     * @param $maidenName
     * @return Item
     */
    public function setMaidenName($maidenName)
    {
        $this->maiden_name = $maidenName;
        return $this;
    }

    /**
     * @return string
     */
    public function getFullName()
    {
        $fullName = $this->first_name . ' ' . $this->middle_name . ' ' . $this->last_name;
        $fullName .= (!is_null($this->maiden_name) && !empty($this->maiden_name)) ? '('.$this->maiden_name.')' : '';
        return trim($fullName);
    }

    /**
     * @return int
     */
    public function getGender()
    {
        return $this->gender;
    }

    /**
     * @param int $gender
     * @return Item
     */
    public function setGender($gender)
    {
        $this->gender = $gender;
        return $this;
    }

    /**
     * @return int
     */
    public function getGotraId()
    {
        return $this->gotra_id;
    }

    /**
     * @param int|null $gotraId
     * @return Item
     */
    public function setGotraId($gotraId)
    {
        $this->gotra_id = $gotraId;
        return $this;
    }

    /**
     * @return int
     */
    public function getDeathId()
    {
        return $this->death_id;
    }

    /**
     * @param int $deathId
     * @return Item
     */
    public function setDeathId($deathId)
    {
        $this->death_id = $deathId;
        return $this;
    }

    /**
     * @return int
     */
    public function getPrivacy()
    {
        return $this->privacy;
    }

    /**
     * @param int $privacy
     * @return Item
     */
    public function setPrivacy($privacy)
    {
        $this->privacy = $privacy;
        return $this;
    }

    /**
     * @return int
     */
    public function getRoot()
    {
        return $this->root;
    }

    /**
     * @param int $root
     * @return $this
     */
    public function setRoot($root)
    {
        $this->root = $root;
        return $this;
    }

    /**
     * @return int
     */
    public function getFatherId()
    {
        return $this->father_id;
    }

    /**
     * @param int|null $fatherId
     * @return Item
     */
    public function setFatherId($fatherId)
    {
        $this->father_id = $fatherId;
        return $this;
    }

    /**
     * @return int
     */
    public function getMotherId()
    {
        return $this->mother_id;
    }

    /**
     * @param int|null $motherId
     * @return Item
     */
    public function setMotherId($motherId)
    {
        $this->mother_id = $motherId;

        return $this;
    }

    /**
     * @return string
     */
    public function getImage()
    {
        return $this->image;
    }

    /**
     * @param string $image
     * @return Item
     */
    public function setImage(string $image)
    {
        $this->image = $image;
        return $this;
    }

    /**
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @param string $email
     * @return Item
     */
    public function setEmail(string $email)
    {
        $this->email = $email;
        return $this;
    }

    /**
     * @return string
     */
    public function getAbout()
    {
        return $this->about;
    }

    /**
     * @param string $about
     * @return Item
     */
    public function setAbout(string $about)
    {
        $this->about = $about;
        return $this;
    }

    /**
     * @return string
     */
    public function getOccupation()
    {
        return $this->occupation;
    }

    /**
     * @param string $occupation
     * @return Item
     */
    public function setOccupation(string $occupation)
    {
        $this->occupation = $occupation;
        return $this;
    }

    /**
     * @return string
     */
    public function getBirthCountry()
    {
        return $this->b_country;
    }

    /**
     * @param string $birthCountry
     * @return $this
     */
    public function setBirthCountry(string $birthCountry)
    {
        $this->b_country = $birthCountry;
        return $this;
    }

    /**
     * @return string
     */
    public function getBirthCity()
    {
        return $this->b_city;
    }

    /**
     * @param string $birthCity
     * @return Item
     */
    public function setBirthCity(string $birthCity)
    {
        $this->b_city = $birthCity;
        return $this;
    }

    /**
     * @return string
     */
    public function getBirthDate()
    {
        return $this->b_date;
    }

    /**
     * @param string $birthdayDate
     * @return Item
     */
    public function setBirthDate(string $birthdayDate)
    {
        $this->b_date = $birthdayDate;
        return $this;
    }

    public function afterSave($insert, $changedAttributes) {
        $this->updateAll(['privacy' => $this->privacy], "user_id=$this->user_id");
        parent::afterSave($insert, $changedAttributes);
    }

    /**
     * @return Item[]
     */
    function getItemsSameUserExcludeItself() {
        $res = [];
        foreach ($this->itemsSameUser as $item) {
            if ($this->id != $item->id)
                $res[] = $item;
        }
        return $res;
    }
}
