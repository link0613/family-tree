<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $searchModel app\modules\admin\models\ResearcherSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Researchers';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="researcher-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create Researcher', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
<?php Pjax::begin(); ?>    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'status',
            'name',
            'email:email',
            'contact_phone',
            // 'contact_skype',
            // 'contact_whats_app',
            // 'state_id',
            // 'district_id',
            // 'district_sub_id',
            // 'city_id',
            // 'custom_location',
            // 'email_paypal:email',
            // 'linked_in',
            // 'business',
            // 'website',
            // 'service',
            // 'extra_1',
            // 'extra_2',
            // 'extra_3',
            // 'extra_4',
            // 'extra_5',
            // 'extra_6',
            // 'extra_7',
            // 'extra_8',
            // 'extra_9',
            // 'extra_10',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
<?php Pjax::end(); ?></div>
