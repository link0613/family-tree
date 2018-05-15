<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\Researcher */

$this->title = 'Create Researcher';
$this->params['breadcrumbs'][] = ['label' => 'Researchers', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="researcher-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
