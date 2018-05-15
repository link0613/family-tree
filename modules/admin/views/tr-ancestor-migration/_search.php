<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\admin\models\TrAncestorMigrationSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="tr-ancestor-migration-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'ship') ?>

    <?= $form->field($model, 'agent') ?>

    <?= $form->field($model, 'port_embarkation') ?>

    <?= $form->field($model, 'date_embarkation') ?>

    <?php // echo $form->field($model, 'port_disembarkation') ?>

    <?php // echo $form->field($model, 'date_disembarkation') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
