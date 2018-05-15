<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\Post */

$this->title = 'Create Post';
$this->params['breadcrumbs'][] = ['label' => 'Blog', 'url' => ['blog']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="block">

    <h2><?= Html::encode($this->title) ?></h2>

    <?= $this->render('_form', [
        'model' => $model,
//        'upload' => $upload,
    ]) ?>

</div>
