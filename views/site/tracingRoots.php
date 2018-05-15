<?php

use yii\bootstrap\ActiveForm;
use yii\bootstrap\Html;

/* @var $this yii\web\View */
/* @var $trApplicant app\models\TrApplicant */
/* @var $trAncestor app\models\TrAncestor */
/* @var $trAncestorDetails app\models\TrAncestorDetails */
/* @var $trAncestorMigration app\models\TrAncestorMigration */
/* @var $trUploadForm app\models\TrUploadForm */


$this->title = Yii::t('app', 'Tracing the roots');
$this->params['breadcrumbs'][] = $this->title;
$form = ActiveForm::begin([
	'id' => 'TracingRootsForm',
	'options' => [
		'enctype' => 'multipart/form-data',
		'class' => 'block ofHidden',
	],
	'enableAjaxValidation' => false,
	'enableClientValidation' => true,
]);
?>
<p class="center bold fs13em">Application for Tracing the Roots</p>
<div class="row">
	<?= $this->render('@app/modules/admin/views/tr-applicant/form/_personalDetails', [
		'form' => $form,
		'model' => $trApplicant,
	]) ?>

	<div class="col-md-3">
		<?= $this->render('@app/modules/admin/views/tr-ancestor/form/_fields', [
			'form' => $form,
			'model' => $trAncestor,
		]) ?>
		<?= $this->render('@app/modules/admin/views/tr-ancestor-details/form/_partB', [
			'form' => $form,
			'model' => $trAncestorDetails,
		]) ?>
	</div>

	<?= $this->render('@app/modules/admin/views/tr-ancestor-migration/form/_fields', [
		'form' => $form,
		'model' => $trAncestorMigration,
	]) ?>

	<?= $this->render('@app/modules/admin/views/tr-applicant/form/_otherAncestors', [
		'form' => $form,
		'model' => $trApplicant,
	]) ?>
</div>
<div class="row">
	<?= $this->render('@app/modules/admin/views/tr-ancestor-details/form/_partE', [
		'form' => $form,
		'model' => $trAncestorDetails,
	]) ?>

	<div id="TrAncestor-files" class="col-md-3">
		<h5>F. Documents attached</h5>
		<?= $form->field($trUploadForm, 'files[]')->fileInput(['multiple' => true]) ?>

		<div class="form-group">
			<?= Html::submitButton('Submit', ['class' => 'btn btn-theme']) ?>
		</div>
	</div>
</div>
<?php
ActiveForm::end();
?>