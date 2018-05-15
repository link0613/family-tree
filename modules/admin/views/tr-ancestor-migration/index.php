<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $searchModel app\modules\admin\models\TrAncestorMigrationSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Tr Ancestor Migrations';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="tr-ancestor-migration-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create Tr Ancestor Migration', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
<?php Pjax::begin(); ?>    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'ship',
            'agent',
            'port_embarkation',
            'date_embarkation',
            // 'port_disembarkation',
            // 'date_disembarkation',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
<?php Pjax::end(); ?></div>
