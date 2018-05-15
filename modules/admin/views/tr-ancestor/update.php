<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\TrAncestor */

$this->title = 'Update Tr Ancestor: ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Tr Ancestors', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="tr-ancestor-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
