<?php

namespace app\components\widgets;


use app\models\State;
use yii\base\Model;

class WLocation extends \yii\base\Widget
{
	/** @var  \yii\widgets\ActiveForm */
	public $form;
	/** @var  Model */
	public $model;

	public $attrState       = 'autoComplete_state';
	public $attrDistrict    = 'autoComplete_district';
	public $attrDistrictSub = 'autoComplete_district_sub';
	public $attrCity        = 'autoComplete_city';
	public $attrCityNeighbor = 'cities_neighbor';

	public $showCityNeighbor = false;

	function run() {
		$citiesNeighborId = $promptCityNeighbor = null;
		$phDependent = 'Set previous first';
		$phReady = 'Begin typing';

		//HTML
		echo $this->form->field($this->model, $this->attrState)->widget(\yii\jui\AutoComplete::className(),
			[
				'options' => [
					'placeholder' => $phReady,
					'id' =>$stateId='trancestor-state',
					'class' =>'form-control',
				],
				'clientOptions' => [
					'source' => State::juiAutoCompleteMap(),
				],
			]);
		echo $this->form->field($this->model, $this->attrDistrict)->widget(\yii\jui\AutoComplete::className(),
			[
				'options' => [
					'placeholder' => $phDependent,
					'id' =>$districtId='trancestor-district',
					'class' =>'form-control',
				],
				'clientOptions' => [
					'source' => [],
				],
			]);
		echo $this->form->field($this->model, $this->attrDistrictSub)->widget(\yii\jui\AutoComplete::className(),
			[
				'options' => [
					'placeholder' => $phDependent,
					'id' =>$districtSubId='trancestor-district_sub',
					'class' =>'form-control',
				],
				'clientOptions' => [
					'source' => [],
				],
			]);
		echo $this->form->field($this->model, $this->attrCity)->widget(\yii\jui\AutoComplete::className(),
			[
				'options' => [
					'placeholder' => $phDependent,
					'id' =>$cityId='trancestor-city',
					'class' =>'form-control',
				],
				'clientOptions' => [
					'source' => [],
				],
			]);

		if ( $this->showCityNeighbor ) {
			echo $this->form->field($this->model, $this->attrCityNeighbor)->dropDownList([], [
				'prompt' => $promptCityNeighbor="Select \"{$this->model->getAttributeLabel($this->attrDistrictSub)}\" first",
				'multiple' => true,
				'id'=>$citiesNeighborId='trancestor-cities_neighbor',
				'size' => 1,
			]);
		}

		//JS
		$jsEnd = <<<JS
function trLocationDisable(id) {
	if ( !id )
		return;
	if ( id=='$citiesNeighborId' ) {
		$('#'+id).html('<option value="">$promptCityNeighbor</option>').attr('size', 1);
	} else {
		$('#'+id).val('').attr('placeholder', '$phDependent').autocomplete("option", "source", []);
	}
}

function trLocationEnable(id, data) {
	if ( !id )
		return;
	if ( id=='$citiesNeighborId' ) {
		var html = '';
		for (var i in data) {
			html+='<option value="'+data[i]+'">'+data[i]+'</option>'
		}
		$('#'+id).html(html).attr('size', 8);
	} else {
		$('#'+id).attr('placeholder', '$phReady').autocomplete("option", "source", data);
	}

}
JS;
		$jsOnReady = <<<JS
//get Districts on State changing
$('#$stateId').on('change',function(){
	trLocationDisable('$districtId');
	trLocationDisable('$districtSubId');
	trLocationDisable('$cityId');
	trLocationDisable('$citiesNeighborId');

	if (!this.value) {
		return;
	}
	$.post('/ajax/districts', {q:this.value}, function(data) {
		trLocationEnable('$districtId', data);
	}, 'json');
});

//get Sub-Districts on District changing
$('#$districtId').on('change',function(){
	trLocationDisable('$districtSubId');
	trLocationDisable('$cityId');
	trLocationDisable('$citiesNeighborId');

	if (!this.value) {
		return;
	}
	$.post('/ajax/districts-sub', {q:this.value}, function(data) {
		trLocationEnable('$districtSubId', data);
	}, 'json');
});

//get Cities on Sub-District changing
$('#$districtSubId').on('change',function(){
	trLocationDisable('$cityId');
	trLocationDisable('$citiesNeighborId');

	if (!this.value) {
		return;
	}
	$.post('/ajax/cities', {q:this.value}, function(data) {
		trLocationEnable('$cityId', data);
		trLocationEnable('$citiesNeighborId', data)
	}, 'json');
});
JS;
		$this->view->registerJs($jsOnReady);
		$this->view->registerJs($jsEnd, \yii\web\View::POS_END);
	}
}