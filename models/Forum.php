<?php

namespace app\models;

/**
 * This is the model class for table "forum".
 *
 * @property integer $id
 * @property integer $question_id
 * @property integer $answer_id
 *
 * @property Answer $answer
 * @property Question $question
 */
class Forum extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'forum';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['question_id', 'answer_id'], 'required'],
            [['question_id', 'answer_id'], 'integer'],
            [
                ['answer_id'],
                'exist',
                'skipOnError' => true,
                'targetClass' => Answer::className(),
                'targetAttribute' => ['answer_id' => 'id']
            ],
            [
                ['question_id'],
                'exist',
                'skipOnError' => true,
                'targetClass' => Question::className(),
                'targetAttribute' => ['question_id' => 'id']
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
            'question_id' => 'Question ID',
            'answer_id' => 'Answer ID',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAnswer()
    {
        return $this->hasOne(Answer::className(), ['id' => 'answer_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getQuestion()
    {
        return $this->hasOne(Question::className(), ['id' => 'question_id']);
    }
}
