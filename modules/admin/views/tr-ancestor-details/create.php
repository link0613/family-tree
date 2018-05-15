<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\TrAncestorDetails */

$this->title = 'Create Tr Ancestor Details';
$this->params['breadcrumbs'][] = ['label' => 'Tr Ancestor Details', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="tr-ancestor-details-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
