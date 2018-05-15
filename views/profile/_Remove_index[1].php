<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $searchModel app\models\ProfileSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Profiles';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="col-md-9">
    <div class="block">
        <div class="profile-index">

            <h1><?= Html::encode($this->title) ?></h1>
            <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

            <p>
                <?= Html::a('Create Profile', ['create'], ['class' => 'btn btn-success']) ?>
            </p>
            <?php Pjax::begin(); ?>    <?= GridView::widget([
                'dataProvider' => $dataProvider,
                'filterModel' => $searchModel,
                'columns' => [
                    ['class' => 'yii\grid\SerialColumn'],

                    'id',
                    'first_name',
                    'last_name',
                    'birth_year',
                    'gorta',
                    // 'gender',

                    ['class' => 'yii\grid\ActionColumn'],
                ],
            ]); ?>
            <?php Pjax::end(); ?></div>

    </div>
</div>
<div class="col-md-3">
    <div class="block">
        <h4>Help block</h4>
        <ul>
            <li>Latest news</li>
            <li>Latest news</li>
            <li>Latest news</li>
            <li>Latest news</li>
        </ul>
    </div>
</div>