<?php

/* @var $this yii\web\View */
/* @var $name string */
/* @var $message string */
/* @var $exception Exception */

use yii\helpers\Html;

$this->title = $name;
?>
    <div class="container">
        <br>
        <br>
        <br>
        <div class="panel panel-danger">
            <div class="panel-heading"><?= Html::encode($this->title) ?></div>
            <div class="panel-body">
                <?= nl2br(Html::encode($message)) ?>
                <hr>
                <p>
                    The above error occurred while the Web server was processing your request.
                </p>
                <p>
                    Please contact us if you think this is a server error. Thank you.
                </p>
                Go to <?= Html::a('Home page', [\yii\helpers\Url::toRoute('/')], ['class' => 'text-primary']) ?>

            </div>
        </div>

    </div>
<?php
$this->registerJs("
$('.navbar ').css('display','none');
");

