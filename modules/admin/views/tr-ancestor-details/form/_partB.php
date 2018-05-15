<?php
/* @var $this yii\web\View */
/* @var $model app\models\TrAncestorDetails */
/* @var $form yii\widgets\ActiveForm */
?>
<div id="TrAncestorDetails-partB">
	<?= $form->field($model, 'property')->textarea(['rows' => 4]) ?>

	<?= $form->field($model, 'family_members')->textarea(['rows' => 10]) ?>
</div>
