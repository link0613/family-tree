<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use dosamigos\tinymce\TinyMce;


$this->title = 'Update : ' . $info->title;
$this->params['breadcrumbs'][] = ['label' => 'Posts', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $info->title, 'url' => ['view', 'id' => $info->id]];
$this->params['breadcrumbs'][] = 'Update';
/* @var $this yii\web\View */
/* @var $info app\models\Post */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="simpleForm terms">

    <?php $form = ActiveForm::begin() ?>
    <?= $form->field($info, 'title')->textInput() ?>

    <?= $form->field($info, 'text')->widget(TinyMce::className(), [
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


    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-theme']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
