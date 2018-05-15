<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var object $educations app\models\Education */
/* @var object $education app\models\Education */

$this->title = 'Update education: ' . $education->place;
$this->params['breadcrumbs'][] = ['label' => 'Education', 'url' => ['education', 'id' => $education->item_id]];
$this->params['breadcrumbs'][] = $this->title;

?>

<div class="block">
    <div class="row">
        <div class="col-md-3 col-xs-12">
            <?php
            echo $this->render('profile_menu', ['id' => $education->item_id]);
            $this->registerJs('
                $(".table.table-bordered.menu-table .fa:eq(2)").addClass("active");
            ');
            ?>
        </div>
        <div class="col-md-9 col-xs-12">
            <h1><?= Html::encode($this->title), ' <span class="small">', $education->item->first_name, ' ', $education->item->last_name, '</span>' ?></h1>

            <?php
            if (Yii::$app->session->getFlash('success')) {
                echo \yii\bootstrap\Alert::widget([
                    'options' => [
                        'class' => 'alert-info',
                    ],
                    'body' => Yii::$app->session->getFlash('success')
                ]);
            }
            ?>
            <h4><u>Education form</u></h4>
            <?php $form = ActiveForm::begin([
                'fieldConfig' => [
                    'inputOptions' => ['class' => ' form-control'],
                ],
            ]); ?>

            <?= $form->field($education, 'country')->textInput(['maxlength' => true]) ?>
            <?= $form->field($education, 'city')->textInput(['maxlength' => true]) ?>
            <?= $form->field($education, 'place')->textInput(['maxlength' => true]) ?>
            <?= $form->field($education, 'begin')->widget(\yii\jui\DatePicker::className(),
                [
                    'clientOptions' =>
                        [
                            'changeYear' => true,
                            'changeMonth' => true,
                            'showAnim' => "slideDown",
                            'yearRange' => "-300y:+6y",
                            ' showButtonPanel' => false

                        ],
                    'attribute' => 'b_date',
                    'options' => [
                        'placeholder' => 'Select date...',
                        'class' => 'form-control',
                    ],
                    'dateFormat' => 'yyyy-MM-dd',
                ]) ?>
            <?= $form->field($education, 'end')->widget(\yii\jui\DatePicker::className(),
                [
                    'clientOptions' =>
                        [
                            'changeYear' => true,
                            'changeMonth' => true,
                            'showAnim' => "slideDown",
                            'yearRange' => "-300y:+6y",
                            ' showButtonPanel' => false

                        ],
                    'attribute' => 'b_date',
                    'options' => [
                        'placeholder' => 'Select date...',
                        'class' => 'form-control',
                    ],
                    'dateFormat' => 'yyyy-MM-dd',
                ]) ?>


            <div class="form-group ">
                <?= Html::submitButton('Save', ['class' => 'btn btn-theme', 'style' => ['width' => '33%']]) ?>
                <hr>
            </div>
            <?php ActiveForm::end(); ?>
        </div>
    </div>

</div>


