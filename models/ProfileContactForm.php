<?php

namespace app\models;

use Yii;
use yii\base\Model;
use app\models\Item;

/**
 * ProfileContactForm is the model behind the profile contact form.
 */
class ProfileContactForm extends Model
{
    public $id;
    public $subject;
    public $message;
    public $sender;


    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
            // name, email, subject and body are required
            [['id', 'subject', 'message'], 'required'],
        ];
    }

    /**
     * @return array customized attribute labels
     */
    public function attributeLabels()
    {
        return [
            'subject' => 'Subject',
            'message' => 'Message',
        ];
    }

    /**
     * Sends an email to the specified email address using the information collected by this model.
     *
     * @param string $fromName
     * @return boolean whether the model passes validation
     */
    public function contact($fromName)
    {
        if ($this->validate()) {
            $item = Item::findOne($this->id);
            if (!$item->email) {
                return false;
            }

            $body = "$fromName (<a href='mailto:{$this->sender}'>{$this->sender}</a>) sent message to you:<br><br>{$this->message}";

            Yii::$app->mail->compose()
                ->setFrom(Yii::$app->params['websiteAdmin'])
                ->setHtmlBody($body)
                ->setTo($item->email)
                ->setSubject($this->subject)
                ->send();

            return true;
        }

        return false;
    }
}
