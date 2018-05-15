<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;


$this->title = 'Contacts';
$this->params['breadcrumbs'][] = $this->title;
/* @var $this yii\web\View */
/* @var object $contacts app\models\Education */
/* @var object $contact app\models\Education */
?>

<div class="block">
    <div class="row">
        <div class="col-md-3 col-xs-12">
            <?php
            echo $this->render('profile_menu');
            $this->registerJs('
                $(".table.table-bordered.menu-table .fa:eq(3)").addClass("active");
            ');
            ?>
        </div>
        <div class="col-md-9 col-xs-12">
            <h1><?= Html::encode($this->title), ' <span class="small">', $contact->item->first_name, ' ', $contact->item->last_name, '</span>' ?></h1>
            <p>
                <button type="button" data-toggle="collapse"
                        onclick="this.innerHTML = this.innerHTML.localeCompare('Hide form') == 0 ? 'Add new contact' : 'Hide form';  "
                        class="btn btn-default styled" data-target="#form">Add new contact
                </button>
            </p>
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
            <div class="collapse" id="form">
                <h4><u>Education form</u></h4>
                <?php $form = ActiveForm::begin([
                    'fieldConfig' => [
                        'inputOptions' => ['class' => ' form-control'],
                    ],
                ]); ?>

                <?= $form->field($contact, 'country')->textInput(['maxlength' => true]) ?>
                <?= $form->field($contact, 'city')->textInput(['maxlength' => true]) ?>
                <?= $form->field($contact, 'phone')->textInput(['maxlength' => true]) ?>
                <?= $form->field($contact, 'post_code')->textInput(['maxlength' => true]) ?>


                <div class="form-group ">
                    <?= Html::submitButton('Save', ['class' => 'btn btn-theme', 'style' => ['width' => '33%']]) ?>
                    <hr>
                </div>
            </div>
            <h4>Contacts list</h4>
            <table class="table table-responsive table-striped">
                <?php
                if (!$contacts) {
                    echo '<span class="text-muted">Nothing to show</span>';
                } else {
                    ?>
                    <tr>
                        <th><?= $contact->getAttributeLabel('country') ?></th>
                        <th><?= $contact->getAttributeLabel('city') ?></th>
                        <th><?= $contact->getAttributeLabel('phone') ?></th>
                        <th><?= $contact->getAttributeLabel('post_code') ?></th>
                        <th></th>
                    </tr>
                    <?php
                    foreach ($contacts as $item) {
                        ?>
                        <tr>
                            <td><?= $item->country ?></td>
                            <td><?= $item->city ?></td>
                            <td><?= $item->phone ?></td>
                            <td><?= $item->post_code ?></td>
                            <td><a href="<?= \yii\helpers\Url::toRoute('/profile/contactedit/' . $item->id) ?>"
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


