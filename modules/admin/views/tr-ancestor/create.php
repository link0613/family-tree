<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\TrAncestor */

$this->title = 'Create Tr Ancestor';
$this->params['breadcrumbs'][] = ['label' => 'Tr Ancestors', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="tr-ancestor-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
