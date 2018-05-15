<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\admin\models\TrAncestorSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="tr-ancestor-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'applicant_id') ?>

    <?= $form->field($model, 'name') ?>

    <?= $form->field($model, 'migrate_year') ?>

    <?= $form->field($model, 'migrate_age') ?>

    <?php // echo $form->field($model, 'caste_name_id') ?>

    <?php // echo $form->field($model, 'profession_id') ?>

    <?php // echo $form->field($model, 'state_id') ?>

    <?php // echo $form->field($model, 'district_id') ?>

    <?php // echo $form->field($model, 'district_sub_id') ?>

    <?php // echo $form->field($model, 'city_id') ?>

    <?php // echo $form->field($model, 'police_station') ?>

    <?php // echo $form->field($model, 'post_office') ?>

    <?php // echo $form->field($model, 'cities_neighbor') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
