<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $searchModel app\modules\admin\models\TrApplicantSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Tr Applicants';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="tr-applicant-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create Tr Applicant', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
<?php Pjax::begin(); ?>    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'name',
            'contact_address',
            'contact_phone',
            'contact_fax',
            // 'contact_email:email',
            // 'profession_id',
            // 'father',
            // 'mother',
            // 'grandfather',
            // 'grandmother',
            // 'great_grandfather',
            // 'great_grandmother',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
<?php Pjax::end(); ?></div>
