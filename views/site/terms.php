<?php

use yii\helpers\Html;
use yii\helpers\Url;

$this->title = $info->title;
?>
<div class="simpleForm terms">
    <h1><?= Html::encode($this->title) ?></h1>

    <?php if (!Yii::$app->user->isGuest && Yii::$app->user->identity->email == 'Admin'): ?>
        <?= Html::a(Yii::t('app', 'Update'), Url::toRoute('termsupdate'), ['class' => 'btn btn-default']) ?>
    <?php endif; ?>

    <article>
        <?= $info->text; ?>
    </article>
    <hr>
    <p>
        <?= Yii::t('app', 'Kulbeli &copy; 2016 All rights reserved.') ?>
    </p>
</div>