<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Profile */
/* @var $form yii\widgets\ActiveForm */

$this->title = 'Invite';
$this->params['breadcrumbs'][] = $this->title;
?>


<div class="block">
    <h1><?= Html::encode($this->title) ?></h1>

    <div class="row">
        <div class="col-xs-12">
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
        </div>
        <div class="col-xs-6 col-md-3 col-sm-4">
            <p><label>Your family email list</label><br>
                <select id="emailList" onchange="sendEmail()" style="width: 100%" class=" form-control">
                    <?php
                    echo '<option value="">---Your family--</option>';
                    foreach ($profiles as $id => $item) {
                        if (strlen($item['email']) > 0 and !is_null($item['email'])) {
                            echo '<option data-email="' . $item['email'] . '" value="' . $id . '">' . $item['name'] . '</option>';
                        }
                    }

                    ?>
                </select>
            </p>
        </div>
        <div class="col-xs-6 col-md-9 col-sm-8">

            <?php $form = ActiveForm::begin(); ?>
            <?= $form->field($model, 'email')->textInput([
                'id' => 'inviteEmail',
                'maxlength' => true,
                'placeholder' => 'Type here email to invite.'
            ]) ?>

            <div class="form-group">
                <?= Html::submitButton('Invite', ['class' => 'btn btn-theme', 'style' => ['width' => '33%']]) ?>
            </div>

            <?php ActiveForm::end(); ?>
        </div>
    </div>

</div>
