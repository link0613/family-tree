<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use \dosamigos\tinymce\TinyMce;

/* @var $this yii\web\View */
/* @var $model app\models\Forum */

$this->title = 'Add question';
$this->params['breadcrumbs'][] = ['label' => 'Forum', 'url' => ['list']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="block">

    <h1><?= Html::encode($this->title) ?></h1>

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'text')->widget(TinyMce::className(), [
        'options' => ['rows' => 12],
        'language' => 'en_GB',
        'clientOptions' => [
            'plugins' => [
                "advlist autolink lists link charmap image  ",
                " visualblocks  ",
                "insertdatetime media table contextmenu paste "
            ],
            'toolbar' => "undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image "
        ]
    ]); ?>

    <div class="form-group">
        <?= Html::submitButton('Add', ['class' => 'btn btn-theme', 'style' => ['width' => '33%']]) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
