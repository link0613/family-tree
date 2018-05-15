<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $searchModel app\models\ProfileSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Create Tree Branch';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="block">
    <div class="row">
        <div class="col-xs-12">
            <h4 class="text-center"> <?= $this->title ?> </h4>
            <hr>
        </div>


        <div class="col-xs-6">
            <h4 class="text-center">Father</h4>
            <?php $form = ActiveForm::begin([
                'enableAjaxValidation' => false,
                'enableClientValidation' => false,
                'fieldConfig' => [
                    'inputOptions' => ['class' => 'input-sm form-control'],
                ],
            ]); ?>
            <?php
            for ($i = 1940; $i < 2000; $i++) {
                $years[$i] = $i;
            }
            ?>

            <?= $form->field($father, 'first_name')->textInput(['maxlength' => true, 'name' => 'Father[first_name]']) ?>

            <?= $form->field($father, 'last_name')->textInput(['maxlength' => true, 'name' => 'Father[last_name]']) ?>

            <?= $form->field($father, 'birth_year')->dropDownList($years, ['name' => 'Father[birth_year]']) ?>

            <?= $form->field($father, 'gorta')->textInput(['maxlength' => true, 'name' => 'Father[gorta]']) ?>


        </div>
        <div class="col-xs-6">

            <h4 class="text-center">Mother</h4>


            <?= $form->field($mother, 'first_name')->textInput(['maxlength' => true, 'name' => 'Mother[first_name]']) ?>

            <?= $form->field($mother, 'last_name')->textInput(['maxlength' => true, 'name' => 'Mother[last_name]']) ?>

            <?= $form->field($mother, 'birth_year')->dropDownList($years, ['name' => 'Mother[birth_year]']) ?>

            <?= $form->field($mother, 'gorta')->textInput(['maxlength' => true, 'name' => 'Mother[gorta]']) ?>

        </div>
        <div class="col-xs-12">
            <hr>
            <h4 class="text-center"><b>Child</b></h4>
            <?= Html::dropDownList('child_id', $selection = null, $profiles, ['class' => 'form-control']) ?>
            <hr>
        </div>
    </div>
    <div class="form-group">
        <?= Html::submitButton('Create Branch', ['style' => ['width' => '100%'], 'class' => 'btn btn-lg btn-theme']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>