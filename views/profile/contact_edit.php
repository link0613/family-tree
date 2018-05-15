<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var object $contacts app\models\Education */
/* @var object $contact app\models\Education */
$person_name = $contact->item->first_name . ' ' . $contact->item->last_name;
$this->title = 'Update contact';
$this->params['breadcrumbs'][] = ['label' => 'Education', 'url' => ['education', 'id' => $contact->item_id]];
$this->params['breadcrumbs'][] = $this->title;

?>

<div class="block">
    <div class="row">
        <div class="col-md-3 col-xs-12">
            <?php
            echo $this->render('profile_menu', ['id' => $contact->item_id]);
            $this->registerJs('
                $(".table.table-bordered.menu-table .fa:eq(3)").addClass("active");
            ');
            ?>
        </div>
        <div class="col-md-9 col-xs-12">
            <h1><?= Html::encode($this->title), ' <span class="small">', $person_name, '</span>' ?></h1>

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
            <h4><u>Contact form</u></h4>
            <?php $form = ActiveForm::begin([
                'fieldConfig' => [
                    'inputOptions' => ['class' => ' form-control'],
                ],
            ]); ?>

            <?= $form->field($contact, 'country')->textInput(['maxlength' => true]) ?>
            <?= $form->field($contact, 'city')->textInput(['maxlength' => true]) ?>
            <?= $form->field($contact, 'phone')->textInput(['maxlength' => true]) ?>
            <?= $form->field($contact, 'post_code')->textInput(['maxlength' => true]) ?>


            <div class="form-group ">
                <?= Html::submitButton('Save', ['class' => 'btn btn-theme', 'style' => ['width' => '33%']]) ?>
                <hr>
            </div>
            <?php ActiveForm::end(); ?>
        </div>
    </div>

</div>


