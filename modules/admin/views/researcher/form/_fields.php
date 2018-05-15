<?php

/* @var $this yii\web\View */
use app\components\widgets\WLocation;

/* @var $model app\models\Researcher */
/* @var $form yii\widgets\ActiveForm */
?>
<div id="Researcher-fields">
	<div class="col-md-4">
		<?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

		<?= $form->field($model, 'email')->textInput(['maxlength' => true]) ?>

		<?= $form->field($model, 'contact_phone')->textInput(['maxlength' => true]) ?>

		<?= $form->field($model, 'contact_skype')->textInput(['maxlength' => true]) ?>

		<?= $form->field($model, 'contact_whats_app')->textInput(['maxlength' => true]) ?>

		<?= $form->field($model, 'email_paypal')->textInput(['maxlength' => true]) ?>
	</div>
	<div class="col-md-4">
		<?= $form->field($model, 'service')->radioList($model->serviceList()) ?>

		<?= $form->field($model, 'linked_in')->textInput(['maxlength' => true]) ?>

		<?= $form->field($model, 'business')->textInput(['maxlength' => true]) ?>

		<?= $form->field($model, 'website')->textInput(['maxlength' => true]) ?>
	</div>
	<div class="form-group col-md-4">
		<h6>Tell us more about your 'In which Village ,city,State, Country are you located?'</h6>
		<?= WLocation::widget([
			'form'  => $form,
			'model' => $model,
		]) ?>
		<?= $form->field($model, 'custom_location')->textInput(['maxlength' => true]) ?>

		<?= $form->field($model, 'address')->textInput(['maxlength' => true]) ?>
	</div>
	<div class="col-md-4">
		<?#= $form->field($model, 'extra_1')->dropDownList([1,2,3,4,5], ['prompt' => 'Select']) ?>

		<?#= $form->field($model, 'extra_2')->dropDownList([1,2,3,4,5], ['prompt' => 'Select']) ?>

		<?#= $form->field($model, 'extra_3')->dropDownList([1,2,3,4,5], ['prompt' => 'Select']) ?>

		<?#= $form->field($model, 'extra_4')->dropDownList([1,2,3,4,5], ['prompt' => 'Select']) ?>

		<?#= $form->field($model, 'extra_5')->textInput(['maxlength' => true]) ?>

		<?#= $form->field($model, 'extra_6')->textInput(['maxlength' => true]) ?>

		<?#= $form->field($model, 'extra_7')->textInput(['maxlength' => true]) ?>

		<?#= $form->field($model, 'extra_8')->textInput(['maxlength' => true]) ?>

		<?#= $form->field($model, 'extra_9')->textInput(['maxlength' => true]) ?>

		<?#= $form->field($model, 'extra_10')->textInput(['maxlength' => true]) ?>
	</div>
</div>