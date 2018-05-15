<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\TrAncestorDetails */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="tr-ancestor-details-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'id')->textInput(['maxlength' => true]) ?>

    <?= $this->render('form/_partB', [
        'form' => $form,
        'model' => $model,
    ]) ?>

    <?= $this->render('form/_partE', [
        'form' => $form,
        'model' => $model,
    ]) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
