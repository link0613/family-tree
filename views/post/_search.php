<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\PostSearch */
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
		'action' => ['blog'],
		'method' => 'get',
        'id' => 'searchForm',
        'fieldConfig' => [
            'inputOptions' => ['class' => ' form-control'],
        ],
	]); ?>

	<?= $form->field($model, 'title') ?>
	<?= $form->field($model, 'text') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-theme']) ?>
    </div>
    <div class="alert alert-danger" id="searchError" style="opacity: 0;">You can search with all empty fields</div>
    <?php ActiveForm::end(); ?>

</div>
<p>&nbsp;</p>


<script async src="//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
<!-- nine -->
<ins class="adsbygoogle"
style="display:inline-block;width:250px;height:400px"
data-ad-client="ca-pub-2673232627079909"
data-ad-slot="9276409073"></ins>
<script>
(adsbygoogle = window.adsbygoogle || []).push({});
</script>
