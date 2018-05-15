<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\imagine\Image;
use  \yii\helpers\Url;

$this->title = 'Image';
$this->params['breadcrumbs'][] = $this->title;
/* @var $this yii\web\View */
/* @var $profile app\models\Item */
/* @var $upload app\models\UploadForm */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="block">

    <div class="row">

        <div class="col-md-3 col-xs-12">
            <?php
            $this->registerJs('
                $(".table.table-bordered.menu-table .fa:eq(1)").addClass("active");
            ');
            echo $this->render('profile_menu');
            ?>
        </div>
        <div class="col-md-9 col-xs-12">
            <h1><?= Html::encode($this->title), ' <span class="small">', $profile->first_name, ' ', $profile->last_name, '</span>' ?></h1>

            <blockquote style="font-size: 16px; margin: 0 0 10px 0;" class="text-info">
                Use images with scale 3:4.
                All uploaded images will be automatically scaled.
            </blockquote>
            <?php
            if (Yii::$app->session->getFlash('success')) {
                echo \yii\bootstrap\Alert::widget([
                    'options' => [
                        'class' => 'alert-info',
                    ],
                    'body' => Yii::$app->session->getFlash('success')
                ]);
            }
            ?>
            <table class="table table-responsive table-striped">
                <thead>
                <tr>
                    <th>
                        Current image
                    </th>
                    <th>
                        Upload new image
                    </th>
                </tr>
                </thead>
                <tbody>
                <tr>
                    <?php
                    $path = Url::to("images/profile/" . $profile->image);
                    $image = file_exists($path) && is_file($path) ? $path : false;
                    ?>
                    <td style="width: 50%; margin: auto;">
                        <?= $image !== false ? Html::img('/' . $path,
                            ['class' => ['img-responsive']]) : '<p class="text-center text-danger"><b>No image</b></p>' ?>
                    </td>
                    <td>
                        <?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]) ?>

                        <?= $form->field($upload, 'image')->fileInput() ?>
                        <div class="form-group">
                            <?= Html::submitButton('Upload',
                                ['class' => 'col-md-3 col-xs-6 pull-right btn btn-theme']) ?>
                        </div>

                        <?php ActiveForm::end(); ?>

                    </td>
                </tr>
                </tbody>
            </table>
        </div>
    </div>
    <?php
    $this->registerJs('
    defineGender();
    
    ')
    ?>

</div>


