<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\FamilyRelation */

$this->title = 'Create Family Relation';
$this->params['breadcrumbs'][] = ['label' => 'Family Relations', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="family-relation-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
