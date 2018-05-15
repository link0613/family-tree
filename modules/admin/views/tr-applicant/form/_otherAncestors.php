<?php
/* @var $this yii\web\View */
/* @var $model app\models\TrApplicant */
/* @var $form yii\widgets\ActiveForm */
?>
<div id="TrApplicant-otherAncestors" class="col-md-3">
	<h5>D. Particulars of other ancestors of the applicant</h5>
	<div class="form-group col-md-12">
		<h6>1. Name of the Parents with nick names, if any</h6>
		<?= $form->field($model, 'father')->textInput([
			'maxlength' => true,
			'placeholder' => $model->getAttributeLabel('father') . '...',
		]) ?>
		<?= $form->field($model, 'mother')->textInput([
			'maxlength' => true,
			'placeholder' => $model->getAttributeLabel('mother') . '...',
		]) ?>
	</div>
	<div class="form-group col-md-12">
		<h6>2. Name of the Grandparents with nicknames, if any</h6>
		<?= $form->field($model, 'grandfather')->textInput([
			'maxlength' => true,
			'placeholder' => $model->getAttributeLabel('grandfather') . '...',
		]) ?>
		<?= $form->field($model, 'grandmother')->textInput([
			'maxlength' => true,
			'placeholder' => $model->getAttributeLabel('grandmother') . '...',
		]) ?>
	</div>
	<div class="form-group col-md-12">
		<h6>3. Name of the Great Grandparents with nick names, if any</h6>
		<?= $form->field($model, 'great_grandfather')->textInput([
			'maxlength' => true,
			'placeholder' => $model->getAttributeLabel('great_grandfather') . '...',
		]) ?>
		<?= $form->field($model, 'great_grandmother')->textInput([
			'maxlength' => true,
			'placeholder' => $model->getAttributeLabel('great_grandmother') . '...',
		]) ?>
	</div>
</div>
