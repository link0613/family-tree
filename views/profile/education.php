<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;


$this->title = 'Education';
$this->params['breadcrumbs'][] = $this->title;
/* @var $this yii\web\View */
/* @var object $educations app\models\Education */
/* @var object $education app\models\Education */
?>

<div class="block">
    <div class="row">

        <div class="col-md-3 col-xs-12">
            <?php
            echo $this->render('profile_menu');
            $this->registerJs('
                $(".table.table-bordered.menu-table .fa:eq(2)").addClass("active");
            ');
            ?>
        </div>
        <div class="col-md-9 col-xs-12">
            <h1>
                <?= Html::encode($this->title), ' <span class="small">', $education->item->first_name, ' ', $education->item->last_name, '</span>' ?>
            </h1>
            <p>
                <button data-toggle="collapse"
                        onclick="this.innerHTML = this.innerHTML.localeCompare('Hide form') == 0 ? 'Add new education' : 'Hide form'; "
                        class="btn btn-default styled" data-target="#form">Add new education
                </button>
            </p>
            <?php if (Yii::$app->session->getFlash('success')) {
                echo \yii\bootstrap\Alert::widget([
                    'options' => [
                        'class' => 'alert-info',
                    ],
                    'body' => Yii::$app->session->getFlash('success')
                ]);
            } ?>
            <div class="collapse" id="form">
                <h4><u>Education form</u></h4>
                <?php $form = ActiveForm::begin([
                    'fieldConfig' => [
                        'inputOptions' => ['class' => ' form-control'],
                    ],
                ]); ?>

                <?= $form->field($education, 'country')->textInput(['maxlength' => true]) ?>
                <?= $form->field($education, 'city')->textInput(['maxlength' => true]) ?>
                <?= $form->field($education, 'place')->textInput(['maxlength' => true]) ?>
                <?= $form->field($education, 'begin')->widget(\yii\jui\DatePicker::className(),
                    [
                        'clientOptions' =>
                            [
                                'changeYear' => true,
                                'changeMonth' => true,
                                'showAnim' => "slideDown",
                                'yearRange' => "-300y:+6y",
                                ' showButtonPanel' => false

                            ],
                        'attribute' => 'b_date',
                        'options' => [
                            'placeholder' => 'Select date...',
                            'class' => 'form-control',
                        ],
                        'dateFormat' => 'yyyy-MM-dd',
                    ]) ?>
                <?= $form->field($education, 'end')->widget(\yii\jui\DatePicker::className(),
                    [
                        'clientOptions' =>
                            [
                                'changeYear' => true,
                                'changeMonth' => true,
                                'showAnim' => "slideDown",
                                'yearRange' => "-300y:+6y",
                                ' showButtonPanel' => false

                            ],
                        'attribute' => 'b_date',
                        'options' => [
                            'placeholder' => 'Select date...',
                            'class' => 'form-control',
                        ],
                        'dateFormat' => 'yyyy-MM-dd',
                    ]) ?>


                <div class="form-group ">
                    <?= Html::submitButton('Save', ['class' => 'btn btn-theme', 'style' => ['width' => '33%']]) ?>
                    <hr>
                </div>
            </div>
            <h4>Education list</h4>
            <table class="table table-responsive table-striped">
                <?php
                if (!$educations) {
                    echo '<span class="text-muted">Nothing to show</span>';
                } else {
                    ?>
                    <tr>
                        <th><?= $education->getAttributeLabel('place') ?></th>
                        <th><?= $education->getAttributeLabel('country') ?></th>
                        <th><?= $education->getAttributeLabel('city') ?></th>
                        <th><?= $education->getAttributeLabel('begin') ?></th>
                        <th><?= $education->getAttributeLabel('end') ?></th>
                        <th></th>
                    </tr>
                    <?php
                    foreach ($educations as $item) {
                        ?>
                        <tr>
                            <td><?= $item->place ?></td>
                            <td><?= $item->country ?></td>
                            <td><?= $item->city ?></td>
                            <td><?= $item->begin ?></td>
                            <td><?= $item->end ?></td>
                            <td><a href="<?= \yii\helpers\Url::toRoute('/profile/educationedit/' . $item->id) ?>"
                                   class="btn btn-xs btn-theme">Update</a></td>
                        </tr>
                        <?php
                    }
                }
                ?>
            </table>
            <?php ActiveForm::end(); ?>
        </div>
    </div>

</div>