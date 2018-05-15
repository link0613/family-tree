<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \app\models\PasswordResetRequestForm */

$this->title = 'Request password reset';
$this->params['breadcrumbs'][] = $this->title;
?>
<nav class="navbar navbar-default navbar-fixed-top">
    <div class="container">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar"
                    aria-expanded="false" aria-controls="navbar">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>

            <a href="/" class="navbar-brand">Kulbeli</a></div>
        <div id="navbar" class="navbar-collapse collapse">
            <ul class="nav navbar-nav navbar-right">
                <li><a class="page-scroll" href="<?php echo \yii\helpers\Url::to(['/']) ?>">
                        <i class="fa fa-home"></i>
                        Back to home page</a></li>
            </ul>
        </div><!--/.nav-collapse -->
    </div>
</nav>


<div class="simpleForm">
    <h1><?= Html::encode($this->title) ?></h1>

    <p>Please fill out your email. A link to reset password will be sent there.</p>
    <?php
    if (Yii::$app->session->getFlash('success')) {
        echo \yii\bootstrap\Alert::widget([
            'options' => [
                'class' => 'alert-info',
            ],
            'body' => Yii::$app->session->getFlash('success')
        ]);
    } elseif (Yii::$app->session->getFlash('error')) {
        echo \yii\bootstrap\Alert::widget([
            'options' => [
                'class' => 'alert-danger',
            ],
            'body' => Yii::$app->session->getFlash('error')
        ]);
    }


    ?>

    <?php $form = ActiveForm::begin(['id' => 'request-password-reset-form']); ?>

    <?= $form->field($model, 'email')->textInput(['autofocus' => true]) ?>

    <div class="form-group">
        <?= Html::submitButton('Send', ['class' => 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

<footer>
    Kulbeli &copy; All rights reserved
</footer>