<?php
namespace app\components\widgets;

use Yii;
use yii\bootstrap\Modal;
use yii\helpers\Html;
use yii\helpers\Url;

class WResearcherModal extends \yii\base\Widget
{
	function link($options = []) {
		$options += [
			'data-toggle'=>"modal",
			'data-target'=>'#'.$this->id,
		];
		echo Html::a(Yii::t('app', 'Become Researcher'), $this->url(), $options);
	}

	function modal() {
		Modal::begin([
			'header' => '<h3>'.Yii::t('app', 'Become Researcher').'</h3>',
			'id' => $this->id,
			'size' => Modal::SIZE_SMALL,
		]);

		echo '<div class="form-group">Thanks for your interest in working on our research projects. Please complete the questionnaire to help us better understand your skill set and areas of expertise. We will reach out to you about projects that are a good fit for your location, skills and expertise.</div>';
		echo '<div class="form-group">';
		echo Html::a('OK', $this->url(), [
			'class' => 'btn btn-theme',
			'style' => ['width' => '100%','color'=>'#fff']
		]);
		echo '</div>';

		Modal::end();
	}

	private function url() {
		return Url::toRoute('site/become-researcher');
	}
}