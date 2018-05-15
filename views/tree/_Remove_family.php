<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $searchModel app\models\ItemSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Items';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="block">
    <div class="row">
        <div class="col-md-3 col-xs-12">
            <?php
            //            echo $this->render('profile_menu', ['id' => $education->item_id]);
            $this->registerJs('
                $(".table.table-bordered.menu-table .fa:eq(2)").addClass("active");
            ');
            ?>
        </div>
        <div class="col-md-9">

            <h1><?= Html::encode($this->title) ?></h1>

            <?php Pjax::begin(); ?>    <?= GridView::widget([
                'dataProvider' => $dataProvider,
                'filterModel' => $searchModel,
                'layout' => "<p class='text-right'>{summary}</p>\n{items}\n{pager}",
                'columns' => [
                    ['class' => 'yii\grid\SerialColumn'],
                    [
                        'attribute' => 'first_name',
                        'filterInputOptions' => [
                            'class' => 'form-control',
                            'placeholder' => 'Type for serach...'
                        ]
                    ],
                    [
                        'attribute' => 'last_name',
                        'filterInputOptions' => [
                            'class' => 'form-control',
                            'placeholder' => 'Type for serach...'
                        ]
                    ],
                    [
                        'attribute' => 'gotra_id',
                        'value' => 'gotra.name'
                    ],
                    'b_date:date',
                    [
                        'headerOptions' => ['width' => '60'],
                        'class' => 'yii\grid\ActionColumn',
                        'template' => '{view} {delete} '

                    ],
                ],
            ]); ?>
            <?php Pjax::end(); ?>
        </div>
    </div>
</div>