<?php

use yii\bootstrap\ActiveForm;
use yii\bootstrap\Html;

/* @var $this yii\web\View */
/* @var $researcher app\models\Researcher */


$this->title = Yii::t('app', 'Become Researcher');
$this->params['breadcrumbs'][] = $this->title;
$form = ActiveForm::begin([
	'id' => 'BecomeResearcherForm',
	'options' => [
		'class' => 'block ofHidden',
	],
	'enableAjaxValidation' => false,
	'enableClientValidation' => true,
]);
?>
<h3 class="center"><?=$this->title?></h3>
<div class="row">
	<?= $this->render('@app/modules/admin/views/researcher/form/_fields', [
		'form' => $form,
		'model' => $researcher,
	]) ?>
</div>
<div class="form-group center">
	<?= Html::submitButton('Submit', ['class' => 'btn btn-theme']) ?>
</div>

<?php
ActiveForm::end();
?>