<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\TrAncestorMigration */

$this->title = 'Create Tr Ancestor Migration';
$this->params['breadcrumbs'][] = ['label' => 'Tr Ancestor Migrations', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="tr-ancestor-migration-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
