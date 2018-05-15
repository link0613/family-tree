<?php
namespace app\components\widgets;

use Yii;
use yii\bootstrap\Modal;
use yii\helpers\Html;
use yii\helpers\Url;

class WTracingRootsModal extends \yii\base\Widget
{
	function link($options = []) {
		$options += [
			'data-toggle'=>"modal",
			'data-target'=>'#'.$this->id,
		];
		echo Html::a(Yii::t('app', 'Tracing the Roots'), $this->url(), $options);
	}

	function modal() {
		Modal::begin([
			'header' => '<h3>'.Yii::t('app', 'Tracing the Roots').'</h3>',
			'id' => $this->id,
			'size' => Modal::SIZE_SMALL,
		]);

		echo '<div class="form-group">Currently we are running pilot program!</div>';
		echo '<div class="form-group">';
		echo Html::a('OK', $this->url(), [
			'class' => 'btn btn-theme ',
			'style' => ['width' => '100%','color'=>'#fff']
		]);
		echo '</div>';

		Modal::end();
	}

	private function url() {
		return Url::toRoute('site/tracing-roots');
	}
}