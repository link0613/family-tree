<?php

namespace app\controllers;


use yii\console\Controller;
use yii\helpers\BaseHtml;
use yii\helpers\Json;

class AjaxController extends Controller
{
	function actionDistricts(){
		return $this->getDropDownMap(new \app\models\District);
	}

	function actionDistrictsSub(){
		return $this->getDropDownMap(new \app\models\DistrictSub);
	}

	function actionCities(){
		return $this->getDropDownMap(new \app\models\City);
	}

	/**
	 * @param \app\models\District|\app\models\DistrictSub|\app\models\City $model
	 * @return string
	 */
	private function getDropDownMap($model) {
		$q = \Yii::$app->request->post('q');
		if ( !$q )
			return '';

		$map = $model->juiAutoCompleteMap($q);
		return Json::encode($map);
	}
}