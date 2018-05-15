<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\TrApplicant */

$this->title = 'Create Tr Applicant';
$this->params['breadcrumbs'][] = ['label' => 'Tr Applicants', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="tr-applicant-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
