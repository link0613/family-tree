<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\ProfileSearch */
/* @var $form yii\widgets\ActiveForm */
?>
<p>
    <a href="#search" class="text-primary" data-toggle="collapse"
       onclick="this.innerHTML = (this.innerHTML.indexOf('<?= Yii::t('app', 'Hide Search Form' ); ?>') != -1) ? '<?= Yii::t('app', 'Show Search Form' ); ?>' : '<?= Yii::t('app', 'Hide Search Form' ); ?>';">
        <?= Yii::t('app', 'Hide Search Form' ); ?>
    </a>
</p>
<div id="search" class="collapse in" aria-expanded="true">

    <?php $form = ActiveForm::begin([
        'action' => ['search'],
        'method' => 'get',
        'id' => 'searchForm',
        'fieldConfig' => [
            'inputOptions' => ['class' => ' form-control'],

        ],
    ]); ?>

    <?= $form->field($model, 'first_name') ?>
    <?= $form->field($model, 'middle_name') ?>
    <?= $form->field($model, 'last_name') ?>
    <?= $form->field($model, 'gotra') ?>
	<?= $form->field($model, 'occupation') ?>
    <?= $form->field($model, 'b_date')->label('Birth Year') ?>
	<?= $form->field($model, 'b_city') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-theme']) ?>
    </div>
    <div class="alert alert-danger" id="searchError" style="opacity: 0;">You can search with all empty fields</div>
    <?php ActiveForm::end(); ?>

</div>
