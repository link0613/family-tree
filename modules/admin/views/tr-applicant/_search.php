<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\admin\models\TrApplicantSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="tr-applicant-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'name') ?>

    <?= $form->field($model, 'contact_address') ?>

    <?= $form->field($model, 'contact_phone') ?>

    <?= $form->field($model, 'contact_fax') ?>

    <?php // echo $form->field($model, 'contact_email') ?>

    <?php // echo $form->field($model, 'profession_id') ?>

    <?php // echo $form->field($model, 'father') ?>

    <?php // echo $form->field($model, 'mother') ?>

    <?php // echo $form->field($model, 'grandfather') ?>

    <?php // echo $form->field($model, 'grandmother') ?>

    <?php // echo $form->field($model, 'great_grandfather') ?>

    <?php // echo $form->field($model, 'great_grandmother') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
