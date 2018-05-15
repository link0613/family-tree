<?php

namespace app\models;

/**
 * This is the model class for table "family_relation".
 *
 * @property integer $id
 * @property integer $mother_id
 * @property integer $father_id
 * @property integer $child_id
 * @property integer $level
 *
 * @property Profile $child
 * @property Profile $father
 * @property Profile $mother
 */
class FamilyRelation extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'family_relation';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['mother_id', 'father_id', 'child_id', 'level'], 'integer'],
            [['child_id', 'level'], 'required'],
            [
                ['child_id'],
                'exist',
                'skipOnError' => true,
                'targetClass' => Profile::className(),
                'targetAttribute' => ['child_id' => 'id']
            ],
            [
                ['father_id'],
                'exist',
                'skipOnError' => true,
                'targetClass' => Profile::className(),
                'targetAttribute' => ['father_id' => 'id']
            ],
            [
                ['mother_id'],
                'exist',
                'skipOnError' => true,
                'targetClass' => Profile::className(),
                'targetAttribute' => ['mother_id' => 'id']
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
            'mother_id' => 'Father',
            'father_id' => 'Mother',
            'child_id' => 'Child',
            'level' => 'Level'
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getChild()
    {
        return $this->hasOne(Profile::className(), ['id' => 'child_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getFather()
    {
        return $this->hasOne(Profile::className(), ['id' => 'father_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMother()
    {
        return $this->hasOne(Profile::className(), ['id' => 'mother_id']);
    }
}
