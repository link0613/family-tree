<?php
namespace app\models;

use yii\base\Model;


class TrUploadForm extends Model
{
	/**
	 * @var \yii\web\UploadedFile[]
	 */
	public $files;

	public function rules()
	{
		return [
			[['files'], 'file', 'maxFiles' => 0,],
		];
	}

	public function upload($trApplicantId, $trAncestorId)
	{
		if ($this->validate()) {
			$path = \Yii::getAlias('@app')."/web/userUploads/tracingRoots/$trApplicantId/$trAncestorId/";
			if ( !is_dir($path) ){
				mkdir($path, 0777, true);
			}
			foreach ($this->files as $file) {
				$file->saveAs($path . $file->baseName . '.' . $file->extension);
			}
			return true;
		} else {
			return false;
		}
	}
}