<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\ForumSearch */
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
        'action' => ['list'],
        'method' => 'get',
        'id' => 'searchForm',
        'fieldConfig' => [
            'inputOptions' => ['class' => ' form-control'],
        ],
    ]); ?>

    <?= $form->field($model, 'text') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-theme']) ?>
    </div>
    <div class="alert alert-danger" id="searchError" style="opacity: 0;">You can search with all empty fields</div>
    <?php ActiveForm::end(); ?>

<script async src="//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
<!-- 11 -->
<ins class="adsbygoogle"
     style="display:inline-block;width:180px;height:280px"
     data-ad-client="ca-pub-2673232627079909"
     data-ad-slot="8718005879"></ins>
<script>
(adsbygoogle = window.adsbygoogle || []).push({});
</script>

</div>

