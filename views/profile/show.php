<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\GridView;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $searchModel app\models\ProfileSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = $profile->first_name . ' ' . $profile->last_name;
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="block">
    <div class="row">

        <div class="col-xs-12">
            <caption><h4>Founded profile</h4></caption>
            <table class="table">
                <tr>
                    <td>First Name</td>
                    <td><?= isset($profile->first_name) ? $profile->first_name : '-----' ?></td>
                </tr>
                <tr>
                    <td>Last Name</td>
                    <td><?= isset($profile->last_name) ? $profile->last_name : '-----' ?></td>
                </tr>
                <tr>
                    <td>Gorta</td>
                    <td><?= isset($profile->gotra->name) ? $profile->gotra->name : '-----' ?></td>
                </tr>
                <tr>
                    <td>Email</td>
                    <td><?= isset($profile->email) ? $profile->email : '-----' ?></td>
                </tr>
                <tr>
                    <td>Birth Year</td>
                    <td><?= $profile->b_date ? date('d M Y', strtotime($profile->b_date)) : '-----' ?></td>
                </tr>
                <tr>
                    <td>Real user</td>
                    <td><?= $profile->root == 1 ? 'Yes' : 'No' ?></td>
                </tr>
            </table>
        </div>
    </div>
</div>
