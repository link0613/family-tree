<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\FamilyRelation */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="family-relation-form">
    <?php $form = ActiveForm::begin([
        'fieldConfig' => [
            'inputOptions' => ['class' => 'input-sm form-control'],
        ],
    ]); ?>

    <?= $form->field($model, 'mother_id')->textInput() ?>

    <?= $form->field($model, 'father_id')->textInput() ?>

    <?= $form->field($model, 'child_id')->textInput() ?>

    <?= $form->field($model, 'level')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update',
            ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
