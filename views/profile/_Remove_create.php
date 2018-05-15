<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\Profile */

$this->title = 'Create Profile';
$this->params['breadcrumbs'][] = ['label' => 'Profiles', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="profile-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?php


    /* @var $this yii\web\View */
    /* @var $model app\models\Profile */
    /* @var $form yii\widgets\ActiveForm */
    ?>

    <div class="profile-form">

        <?php $form = \yii\widgets\ActiveForm::begin([
            'fieldConfig' => [
                'inputOptions' => ['class' => 'input-sm form-control'],
            ],
        ]); ?>

        <?php
        $gender = [0 => 'Female', 1 => 'Male']
        ?>
        <?= $form->field($model, 'first_name')->textInput(['maxlength' => true]) ?>

        <?= $form->field($model, 'last_name')->textInput(['maxlength' => true]) ?>


        <?= $form->field($model, 'gorta')->textInput(['maxlength' => true]) ?>

        <?= $form->field($model, 'gender')->dropDownList($gender) ?>

        <div class="form-group">
            <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update',
                ['class' => $model->isNewRecord ? 'btn btn-theme' : 'btn btn-theme']) ?>
        </div>

        <?php ActiveForm::end(); ?>

    </div>

</div>
