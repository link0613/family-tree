<?php
use yii\helpers\Html;
use app\assets\AppAsset;

/* @var $this \yii\web\View */
/* @var $content string */

AppAsset::register($this);
?>
<?php $this->beginPage() ?>
    <!DOCTYPE html>
    <html lang="<?= Yii::$app->language ?>">
    <head>
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta charset="<?= Yii::$app->charset ?>">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <?= Html::csrfMetaTags() ?>
        <?php
		if( ! $this->title )
			$this->title = 'Kulbeli: India first Family Tree website, Create your family tree';
        ?>
        <title><?= Html::encode($this->title) ?></title>
        <?php $this->head() ?>
    </head>
    <body id="home">
    <?= $this->beginBody(); ?>

    <?php
    foreach (Yii::$app->session->getAllFlashes() as $key => $message) {
        if ( is_array($message) ) {
            $message = join('<br>',$message);
        }
        echo \yii\bootstrap\Alert::widget([
            'options' => [
                'class' => "alert-$key",
            ],
            'body' => $message
        ]);
    }
    ?>

    <?= $content; ?>

    <?= $this->endBody(); ?>
    </body>
    </html>
<?php $this->endPage() ?>