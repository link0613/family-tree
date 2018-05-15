<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Profile */

$this->title = 'Update My Profile ';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="block">
    <h4><?= Html::encode($this->title) ?></h4>
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>
</div>
