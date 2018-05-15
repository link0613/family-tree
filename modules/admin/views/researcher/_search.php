<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\admin\models\ResearcherSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="researcher-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'status') ?>

    <?= $form->field($model, 'name') ?>

    <?= $form->field($model, 'email') ?>

    <?= $form->field($model, 'contact_phone') ?>

    <?php // echo $form->field($model, 'contact_skype') ?>

    <?php // echo $form->field($model, 'contact_whats_app') ?>

    <?php // echo $form->field($model, 'state_id') ?>

    <?php // echo $form->field($model, 'district_id') ?>

    <?php // echo $form->field($model, 'district_sub_id') ?>

    <?php // echo $form->field($model, 'city_id') ?>

    <?php // echo $form->field($model, 'custom_location') ?>

    <?php // echo $form->field($model, 'email_paypal') ?>

    <?php // echo $form->field($model, 'linked_in') ?>

    <?php // echo $form->field($model, 'business') ?>

    <?php // echo $form->field($model, 'website') ?>

    <?php // echo $form->field($model, 'service') ?>

    <?php // echo $form->field($model, 'extra_1') ?>

    <?php // echo $form->field($model, 'extra_2') ?>

    <?php // echo $form->field($model, 'extra_3') ?>

    <?php // echo $form->field($model, 'extra_4') ?>

    <?php // echo $form->field($model, 'extra_5') ?>

    <?php // echo $form->field($model, 'extra_6') ?>

    <?php // echo $form->field($model, 'extra_7') ?>

    <?php // echo $form->field($model, 'extra_8') ?>

    <?php // echo $form->field($model, 'extra_9') ?>

    <?php // echo $form->field($model, 'extra_10') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
