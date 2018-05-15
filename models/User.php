<?php

namespace app\models;

use app\components\interfaces\EmailConfirmed;
use Yii;

/**
 * This is the model class for table "user".
 *
 * @property integer $id
 * @property string $email
 * @property string $password_hash
 * @property string $status
 *
 * @property Answer[] $answers
 * @property Item[] $items
 * @property Item $itemRoot
 * @property Question[] $questions
 */
class User extends Identity  implements EmailConfirmed
{
    const STATUS_unverified = 0;
    const STATUS_active = 1;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'user';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['email', 'password_hash'], 'required'],
            [['email', 'password_hash'], 'string', 'max' => 100],
            [['email'], 'unique'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'email' => 'Email',
            'password_hash' => 'Password Hash',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAnswers()
    {
        return $this->hasMany(Answer::className(), ['user_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getItems()
    {
        return $this->hasMany(Item::className(), ['user_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getQuestions()
    {
        return $this->hasMany(Question::className(), ['user_id' => 'id']);
    }

    function getItemRoot() {
        foreach ($this->items as $item) {
            if ($item->root)
                return $item;
        }
        return null;
    }

    static function emailConfirmed($email) {
        return self::updateAll(['status' => self::STATUS_active], 'email = :email', [':email' => $email]);
    }
}
