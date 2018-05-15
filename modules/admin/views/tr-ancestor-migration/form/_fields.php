<?php
/* @var $this yii\web\View */
/* @var $model app\models\TrAncestorMigration */
/* @var $form yii\widgets\ActiveForm */
?>
<div id="TrAncestorMigration" class="col-md-3">
	<h5>C. Details of Migration</h5>
<?= $form->field($model, 'ship')->textInput([
	'maxlength' => true,
	'placeholder' => $model->getAttributeLabel('ship') . '...',
]) ?>

<?= $form->field($model, 'agent')->textInput([
	'maxlength' => true,
	'placeholder' => $model->getAttributeLabel('agent') . '...',
]) ?>

<?= $form->field($model, 'port_embarkation')->textInput([
	'maxlength' => true,
	'placeholder' => $model->getAttributeLabel('port_embarkation') . '...',
]) ?>

<?= $form->field($model, 'date_embarkation')->widget(\yii\jui\DatePicker::className(),
	[
		'clientOptions' =>
			[
				'changeYear' => true,
				'changeMonth' => true,
				'showAnim' => "slideDown",
				'defaultDate' => '-20y',
				'yearRange' => "-400y:+0y",
				'showButtonPanel' => false
			],
		'attribute' => 'date_embarkation',
		'options' => [
			'placeholder' => $model->getAttributeLabel('date_embarkation') . '...',
			'class' => 'form-control',
		],
		'dateFormat' => 'yyyy-MM-dd',
	]) ?>

<?= $form->field($model, 'port_disembarkation')->textInput([
	'maxlength' => true,
	'placeholder' => $model->getAttributeLabel('port_disembarkation') . '...',
]) ?>

<?= $form->field($model, 'date_disembarkation')->widget(\yii\jui\DatePicker::className(),
	[
		'clientOptions' =>
			[
				'changeYear' => true,
				'changeMonth' => true,
				'showAnim' => "slideDown",
				'defaultDate' => '-20y',
				'yearRange' => "-400y:+0y",
				'showButtonPanel' => false
			],
		'attribute' => 'date_disembarkation',
		'options' => [
			'placeholder' => $model->getAttributeLabel('date_disembarkation') . '...',
			'class' => 'form-control',
		],
		'dateFormat' => 'yyyy-MM-dd',
	]) ?>
</div>