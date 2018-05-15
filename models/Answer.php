<?php

namespace app\models;
use app\models\Item;
use Yii;
use yii\base\Component;

/**
 * This is the model class for table "answer".
 *
 * @property integer $id
 * @property string $text
 * @property integer $user_id
 * @property string $date
 *
 * @property User $user
 * @property Forum[] $forums
 */
class Answer extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'answer';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['text', 'user_id', 'date'], 'required'],
            [['text'], 'string'],
            [['user_id'], 'integer'],
            [['date'], 'safe'],
            [
                ['user_id'],
                'exist',
                'skipOnError' => true,
                'targetClass' => User::className(),
                'targetAttribute' => ['user_id' => 'id']
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
            'text' => 'Answer',
            'user_id' => 'User',
            'date' => 'Date',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
       return $this->hasOne(User::className(), ['id' => 'user_id']);
    }

	public function GetUserName($id)
	{
		/** @var Item $user */
		$user = Item::find()
			->where(['user_id' => $id    ])
            ->one();

		return $user->getFullName();
	}

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getForums()
    {
        return $this->hasMany(Forum::className(), ['answer_id' => 'id']);
    }
}
