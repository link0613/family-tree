<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\TrAncestor */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Tr Ancestors', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="tr-ancestor-view">

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
            'applicant_id',
            'name',
            'migrate_year',
            'migrate_age',
            'caste_name_id',
            'profession_id',
            'state_id',
            'district_id',
            'district_sub_id',
            'city_id',
            'police_station',
            'post_office',
            'cities_neighbor',
        ],
    ]) ?>

</div>
