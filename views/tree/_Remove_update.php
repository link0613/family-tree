<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\FamilyRelation */

$this->title = 'Update Family Relation: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Family Relations', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="family-relation-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
