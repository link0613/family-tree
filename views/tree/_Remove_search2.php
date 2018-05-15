<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $searchModel app\models\ProfileSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Search For Relatives';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="block">

    <h4><?= Html::encode($this->title) ?></h4>
    <?php echo $this->render('_profileSearch', ['model' => $searchModel]); ?>
    <hr>
    <div id="result">
        <?php
        if (!is_array($dataProvider) and count($dataProvider->getModels()) > 0) {
            echo '<h4>Search Results</h4>';
            echo '<table class="table">';
            echo '<tr>';
            echo '<th>First Name</th>';
            echo '<th>Last Name</th>';
            echo '<th>Gorta</th>';
            echo '</tr>';

            foreach ($dataProvider->getModels() as $model) {
                ?>

                <tr>
                    <td><?= $model->first_name ?></td>
                    <td><?= $model->last_name ?></td>
                    <td><?= $model->gorta ?></td>
                </tr>
                <?php
            }
            echo '</table>';
        } elseif (Yii::$app->request->get('ProfileSearch')) {
            echo '<h4 class="text-danger">No Results</h4>';

        }
        ?>
    </div>

</div>
