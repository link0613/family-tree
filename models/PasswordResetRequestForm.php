<?php
namespace app\models;

use Yii;
use yii\base\Model;
use app\models\User;
use yii\helpers\Url;

/**
 * Password reset request form
 */
class PasswordResetRequestForm extends Model
{
    public $email;


    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['email', 'trim'],
            ['email', 'required'],
            ['email', 'email'],
            [
                'email',
                'exist',
                'targetClass' => '\app\models\User',
                'message' => 'There is no user with such email.'
            ],
        ];
    }

    /**
     * Sends an email with a link, for resetting the password.
     *
     * @return boolean whether the email was send
     */
    public function sendEmail()
    {

        /* @var $user User */
        $user = User::findOne([
            'email' => $this->email,
        ]);

        if (!$user) {
            return false;
        }

        if (!User::isPasswordResetTokenValid($user->password_reset_token)) {
            $user->generatePasswordResetToken();
            if (!$user->save()) {
                return false;
            }
        }
        $url = Url::to(['site/reset', 'token' => $user->password_reset_token], true);

        return Yii::$app->mail->compose()
            ->setHtmlBody('Your password reset link: ' . $url)
            ->setFrom('Info@kulbeli.com')
            ->setTo($user->email)
            ->setSubject('Kulbeli password reset request')
            ->send();
    }
}
