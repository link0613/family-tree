<?php
use app\models\Profession;

/* @var $this yii\web\View */
/* @var $model app\models\TrApplicant */
/* @var $form yii\widgets\ActiveForm */
?>
<div id="TrApplicant-personalDetails" class="col-md-3">
	<h5>A. Personal details of the applicant</h5>
	<?= $form->field($model, 'name')->textInput([
		'maxlength' => true,
		'placeholder' => $model->getAttributeLabel('name') . '...',
	]) ?>

	<div class="form-group col-md-12">
		<h6>Contact particulars</h6>
		<?= $form->field($model, 'contact_address')->textInput([
			'maxlength' => true,
			'placeholder' => $model->getAttributeLabel('contact_address') . '...',
		]) ?>

		<?= $form->field($model, 'contact_phone')->textInput([
			'maxlength' => true,
			'placeholder' => $model->getAttributeLabel('contact_phone') . '...',
		]) ?>

		<?= $form->field($model, 'contact_fax')->textInput([
			'maxlength' => true,
			'placeholder' => $model->getAttributeLabel('contact_fax') . '...',
		]) ?>

		<?= $form->field($model, 'contact_email')->textInput([
			'maxlength' => true,
			'placeholder' => $model->getAttributeLabel('contact_email') . '...',
		]) ?>
	</div>

	<?= $form->field($model, 'autoComplete_profession')->widget(\yii\jui\AutoComplete::className(),
		[
			'options' => [
				'placeholder' => 'Begin typing',
				'class' =>'form-control',
			],
			'clientOptions' => [
				'source' => Profession::juiAutoCompleteMap(),
			],
		]);
	?>
</div>

