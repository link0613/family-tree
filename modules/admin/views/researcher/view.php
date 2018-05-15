<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Researcher */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Researchers', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="researcher-view">

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
            'status',
            'name',
            'email:email',
            'contact_phone',
            'contact_skype',
            'contact_whats_app',
            'state_id',
            'district_id',
            'district_sub_id',
            'city_id',
            'custom_location',
            'email_paypal:email',
            'linked_in',
            'business',
            'website',
            'service',
            'extra_1',
            'extra_2',
            'extra_3',
            'extra_4',
            'extra_5',
            'extra_6',
            'extra_7',
            'extra_8',
            'extra_9',
            'extra_10',
        ],
    ]) ?>

</div>
