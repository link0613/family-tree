<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\TrAncestor */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="tr-ancestor-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'applicant_id')->textInput(['maxlength' => true]) ?>

    <?= $this->render('form/_fields', [
        'form' => $form,
        'model' => $model,
    ]) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
