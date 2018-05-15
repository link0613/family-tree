<?php

use app\components\widgets\WLocation;
use app\models\CasteName;
use app\models\Profession;

/* @var $this yii\web\View */
/* @var $model app\models\TrApplicant */
/* @var $form yii\widgets\ActiveForm */
$promptDependent = 'Select previous first';
?>
<div id="TrAncestor-fields">
	<h5>B. Particulars of the Ancestor with whom the link is to be established</h5>
	<?= $form->field($model, 'name')->textInput([
		'maxlength' => true,
		'placeholder' => $model->getAttributeLabel('name') . '...',
	]) ?>

	<?= $form->field($model, 'migrate_year')->textInput([
		'placeholder' => $model->getAttributeLabel('migrate_year') . '...',
	]) ?>

	<?= $form->field($model, 'migrate_age')->textInput([
		'placeholder' => $model->getAttributeLabel('migrate_age') . '...',
	]) ?>

	<?= $form->field($model, 'autoComplete_caste_name')->widget(\yii\jui\AutoComplete::className(),
		[
			'options' => [
				'placeholder' => 'Begin typing',
				'class' =>'form-control',
			],
			'clientOptions' => [
				'source' => CasteName::juiAutoCompleteMap(),
			],
		]);
	?>

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

	<?= WLocation::widget([
		'form'             => $form,
		'model'            => $model,
		'showCityNeighbor' => true,
	]) ?>

	<?= $form->field($model, 'police_station')->textInput([
		'maxlength' => true,
		'placeholder' => $model->getAttributeLabel('police_station') . '...',
	]) ?>

	<?= $form->field($model, 'post_office')->textInput([
		'maxlength' => true,
		'placeholder' => $model->getAttributeLabel('post_office') . '...',
	]) ?>
</div>