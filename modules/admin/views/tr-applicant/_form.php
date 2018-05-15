<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\TrApplicant */
/* @var $form yii\widgets\ActiveForm */

?>

<div class="tr-applicant-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $this->render('form/_personalDetails', [
        'form' => $form,
        'model' => $model,
    ]) ?>

    <?= $this->render('form/_otherAncestors', [
        'form' => $form,
        'model' => $model,
    ]) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
