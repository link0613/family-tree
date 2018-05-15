<?php
namespace app\models;

use yii\base\Model;
use app\models\User;

/**
 * Signup form
 */
class SignupForm extends Model
{
    public $email;
    public $password;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['email', 'password'], 'required'],
            ['email', 'trim'],

            ['email', 'email'],
            [['email', 'password'], 'string', 'max' => 255, 'min' => 6],

            [
                'email',
                'unique',
                'targetClass' => '\app\models\User',
                'message' => 'This email address has already been taken.'
            ],

            ['password', 'string', 'min' => 6],

        ];
    }

    /**
     * Signs user up.
     *
     * @return User|null the saved model or null if saving fails
     */
    public function signup()
    {
        if (!$this->validate()) {
            return null;
        }
        $user = new User();
        $user->email = $this->email;
        $user->setPassword($this->password);
        if (!$user->save())
            return null;

        /** @var \app\components\MailHelper $mh */
        $mh = \Yii::$app->mailHelper;
        $mh->sendConfirmation($user->email, $user);

        return $user;
    }
}
