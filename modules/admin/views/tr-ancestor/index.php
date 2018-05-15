<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $searchModel app\modules\admin\models\TrAncestorSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Tr Ancestors';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="tr-ancestor-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create Tr Ancestor', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
<?php Pjax::begin(); ?>    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'applicant_id',
            'name',
            'migrate_year',
            'migrate_age',
            // 'caste_name_id',
            // 'profession_id',
            // 'state_id',
            // 'district_id',
            // 'district_sub_id',
            // 'city_id',
            // 'police_station',
            // 'post_office',
            // 'cities_neighbor',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
<?php Pjax::end(); ?></div>
