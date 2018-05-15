<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\GridView;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $searchModel app\models\ProfileSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'My Profile';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="block">
    <div class="row">
        <div class="col-md-3 col-xs-12">
            <?php

            echo $this->render('profile_menu', ['id' => $profile->id]);
            ?>
        </div>
        <div class="col-md-9 col-xs-12">
            <caption><h4>Your Profile Information</h4></caption>
            <table class="table">

                <tr>
                    <td>First Name</td>
                    <td><?= $profile->first_name ?></td>
                </tr>
                <tr>
                    <td>Last Name</td>
                    <td><?= $profile->last_name ?></td>
                </tr>
                <tr>
                    <td>Gorta</td>
                    <td><?= $profile->gotra->name ?></td>
                </tr>
                <tr>
                    <td>Email</td>
                    <td><?= Yii::$app->user->identity->email ?></td>
                </tr>
                <tr>
                    <td>Birth Year</td>
                    <td><?= $profile->b_date ?></td>
                </tr>
            </table>
            <a href="<?= Url::toRoute('profile/view/' . $profile->id) ?>" class="btn btn-theme">Update My Profile</a>
        </div>
    </div>

    <br>
    <br>
    <br>
</div>
