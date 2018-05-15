<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\TrApplicant */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Tr Applicants', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="tr-applicant-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'name',
            'contact_address',
            'contact_phone',
            'contact_fax',
            'contact_email:email',
            'profession_id',
            'father',
            'mother',
            'grandfather',
            'grandmother',
            'great_grandfather',
            'great_grandmother',
        ],
    ]) ?>

</div>
