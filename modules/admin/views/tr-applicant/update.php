<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\TrApplicant */

$this->title = 'Update Tr Applicant: ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Tr Applicants', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="tr-applicant-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
