<?php

namespace app\models;

use Yii;
use yii\base\Model;

/**
 * ContactForm is the model behind the contact form.
 */
class ContactForm extends Model
{
    public $name;
    public $email;
    public $message;
    public $verifyCode;


    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
            // name, email, subject and body are required
            [['name', 'email', 'message'], 'required'],
            // email has to be a valid email address
            ['email', 'email'],
            // verifyCode needs to be entered correctly
            ['verifyCode', 'captcha', 'message' => 'The verification code is incorrect, click on code to refresh'],
        ];
    }

    /**
     * @return array customized attribute labels
     */
    public function attributeLabels()
    {
        return [
            'verifyCode' => 'Type letters from image',
            'name' => 'Your Name',
            'email' => 'Email',
            'message' => 'Message',
        ];
    }

    /**
     * Sends an email to the specified email address using the information collected by this model.
     * @param string $email the target email address
     * @return boolean whether the model passes validation
     */
    public function contact($email)
    {
        if ($this->validate()) {
            $body = $this->message . '<br>' . 'From: ' . $this->name . ' ' . $this->email;
            Yii::$app->mail->compose()
                ->setFrom(Yii::$app->params['websiteAdmin'])
                ->setHtmlBody($body)
                ->setTo($email)
                ->setSubject('Website contact form')
                ->send();
            return true;
        }
        return false;
    }
}
