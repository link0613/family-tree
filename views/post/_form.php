<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use dosamigos\tinymce\TinyMce;

/* @var $this yii\web\View */
/* @var $model app\models\Post */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="post-form">

    <?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]) ?>
    <?= $form->field($model, 'title')->textInput() ?>

    <?= $form->field($model, 'text')->widget(TinyMce::className(), [
        'options' => ['rows' => 20],
        'language' => 'en_GB',
        'clientOptions' => [
            'plugins' => [
                "advlist autolink lists link charmap image  ",
                " visualblocks  ",
                "insertdatetime media table contextmenu paste "
            ],
            'toolbar' => "undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image "
        ]
    ])->label(false); ?>
    <?= $form->field($model, 'description')->textInput() ?>


    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update',
            ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
