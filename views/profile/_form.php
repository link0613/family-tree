<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Profile */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="profile-form">

    <?php $form = ActiveForm::begin([
        'fieldConfig' => [
            'inputOptions' => ['class' => 'input-sm form-control'],
        ],
    ]); ?>

    <?php
    $gender = [0 => 'Female', 1 => 'Male']
    ?>
    <?= $form->field($model, 'first_name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'last_name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'birth_year')->textInput() ?>

    <?= $form->field($model, 'birth_city')->textInput() ?>

    <?= $form->field($model, 'gorta')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'gender')->dropDownList($gender) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update',
            ['class' => $model->isNewRecord ? 'btn btn-theme' : 'btn btn-theme']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
