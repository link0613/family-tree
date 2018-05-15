<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model \app\models\custom\Item */
/* @var $form yii\widgets\ActiveForm */
?>
<p>
    <a href="#search" class="text-primary" data-toggle="collapse"
       onclick="this.innerHTML = this.innerHTML.localeCompare('Hide Search Form')==0 ? 'Show Search Form' : 'Hide Search Form'">Hide
        Search Form</a>
</p>
<div id="search" class="collapse in" aria-expanded="true">

    <?php $form = ActiveForm::begin([
        'action' => ['search'],
        'method' => 'get',
        'fieldConfig' => [
            'inputOptions' => ['class' => 'input-sm form-control'],
        ],
    ]); ?>


    <?= $form->field($model, 'first_name') ?>

    <?= $form->field($model, 'last_name') ?>

    <?= $form->field($model, 'birth_year') ?>

    <?= $form->field($model, 'gorta') ?>


    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-theme']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
