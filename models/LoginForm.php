<?php

namespace app\models;

use Yii;
use yii\base\Model;

/**
 * LoginForm is the model behind the login form.
 *
 * @property User|null $user This property is read-only.
 *
 */
class LoginForm extends Model
{
    public $username;
    public $password;
    public $rememberMe = true;

    private $_user = false;


    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
            // username and password are both required
            [['username', 'password'], 'required'],
            // rememberMe must be a boolean value
            ['rememberMe', 'boolean'],
            // password is validated by validatePassword()
            ['password', 'validatePassword'],
            ['username', 'filter', 'filter' => function() {
                if (!$this->hasErrors() && $this->getUser() && $this->getUser()->status == User::STATUS_unverified) {
                    $this->addError('username', "Email is not verified. We've already sent confirmation mail. Check your mailbox including spam to verify your email address.");

                    //because of ajax validation email sends multiple times
                    $k = self::className().'_sendConfirmationEmail';
                    $lastSendTime = Yii::$app->session->get($k);
                    if ( !$lastSendTime || (time() - $lastSendTime)>60*5 ) {
                        /** @var \app\components\MailHelper $mh */
                        $mh = \Yii::$app->mailHelper;
                        $mh->sendConfirmation($this->getUser()->email, $this->getUser());
                        Yii::$app->session->set($k, time());
                    }
                }
                return $this->username;
            }],
        ];
    }

    /**
     * Validates the password.
     * This method serves as the inline validation for password.
     *
     * @param string $attribute the attribute currently being validated
     * @param array $params the additional name-value pairs given in the rule
     */
    public function validatePassword($attribute, $params)
    {
        if (!$this->hasErrors()) {
            $user = $this->getUser();

            if (!$user || !$user->validatePassword($this->password)) {
                $this->addError($attribute, 'Incorrect username or password.');
            }
        }
    }

    /**
     * Logs in a user using the provided username and password.
     * @return boolean whether the user is logged in successfully
     */
    public function login()
    {
        if ($this->validate()) {
            return Yii::$app->user->login($this->getUser(), $this->rememberMe ? 3600 * 24 * 30 : 0);
        }
        return false;
    }

    /**
     * Finds user by [[username]]
     *
     * @return User|null
     */
    public function getUser()
    {
        if ($this->_user === false) {
            $this->_user = User::findByUsername($this->username);
        }

        return $this->_user;
    }
}
