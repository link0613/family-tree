<?php

namespace app\models;

/**
 * This is the model class for table "profile".
 *
 * @property integer $id
 * @property string $first_name
 * @property string $last_name
 * @property integer $birth_year
 * @property string $gorta
 * @property integer $gender
 * @property integer $creator_id
 * @property integer $prime
 *
 * @property FamilyRelation[] $familyRelations
 * @property FamilyRelation[] $familyRelations0
 * @property FamilyRelation[] $familyRelations1
 * @property User $creator
 */
class Profile extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'profile';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [
                [
                    'first_name',
                    'last_name',
                    'birth_year',
                    'gorta',
                    'gender',
                    'creator_id'
                ],
                'required'
            ],
            [['birth_year', 'gender', 'creator_id', 'prime'], 'integer'],
            [['first_name', 'last_name', 'gorta'], 'string', 'max' => 100],
            [
                ['creator_id'],
                'exist',
                'skipOnError' => true,
                'targetClass' => User::className(),
                'targetAttribute' => ['creator_id' => 'id']
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
            'first_name' => 'First Name',
            'last_name' => 'Last Name',
            'birth_year' => 'Birth Year',
            'gorta' => 'Gorta',
            'gender' => 'Gender',
            'creator_id' => 'Creator',
            'prime' => 'Prime',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getFamilyRelations()
    {
        return $this->hasMany(FamilyRelation::className(), ['child_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getFamilyRelations0()
    {
        return $this->hasMany(FamilyRelation::className(), ['father_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getFamilyRelations1()
    {
        return $this->hasMany(FamilyRelation::className(), ['mother_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCreator()
    {
        return $this->hasOne(User::className(), ['id' => 'creator_id']);
    }
}
