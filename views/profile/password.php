<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Profile */
/* @var $form yii\widgets\ActiveForm */

$this->title = 'Change password';
$this->params['breadcrumbs'][] = $this->title;
?>


<div class="block">
    <h1><?= Html::encode($this->title) ?></h1>
    <?php
    if (Yii::$app->session->getFlash('success')) {
        echo \yii\bootstrap\Alert::widget([
            'options' => [
                'class' => 'alert-info',
            ],
            'body' => Yii::$app->session->getFlash('success')
        ]);
    }
    ?>
    <?php $form = ActiveForm::begin(); ?>


    <?= $form->field($user, 'password')->input('password', ['maxlength' => true]) ?>

    <div class="form-group">
        <?= Html::submitButton('Change', ['class' => 'btn btn-theme']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
