<?php
/* @var $this yii\web\View */
/* @var $model app\models\TrAncestorDetails */
/* @var $form yii\widgets\ActiveForm */
?>
<div id="TrAncestorDetails-partE" class="col-md-3">
	<h5>E. General</h5>
	<?= $form->field($model, 'correspondence')->textarea(['rows' => 4]) ?>

	<?= $form->field($model, 'stories')->textarea(['rows' => 4]) ?>

	<?= $form->field($model, 'other')->textarea(['rows' => 4]) ?>
</div>
