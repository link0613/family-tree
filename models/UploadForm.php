<?php
namespace app\models;

use yii\base\Model;
use yii\helpers\Url;
use Yii;

class UploadForm extends Model
{

    public $image;


    public function rules()
    {
        return [
            [['image'], 'file', 'skipOnEmpty' => false, 'extensions' => 'png, jpg, jpeg, PNG, JPG, JPEG'],
        ];
    }

    public function upload()
    {
        if ($this->validate()) {
            $name = Yii::$app->security->generateRandomString(35) . '.' . $this->image->extension;
            $save_path = Url::to('images/profile') . '/' . $name;
            $this->image->saveAs($save_path);
            return $name;
        } else {
            return false;
        }
    }
}