<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $searchModel app\models\FamilyRelationSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'My Family Tree';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="block">
    <h4><?= Html::encode($this->title) ?></h4>
    <table class="table">
        <tr>
            <th>Name</th>
            <th>Gorta</th>
            <th>Birth year</th>
        </tr>
        <?php
        foreach ($relations as $relation) {
            ?>
            <tr>
                <td><?= $relation->first_name, ' ', $relation->last_name ?></td>
                <td><?= $relation->gorta ?></td>
                <td><?= $relation->birth_year ?></td>
            </tr>
            <?php
        }
        ?>
    </table>

</div>
