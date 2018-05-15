<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\models\Item;

$this->title = 'Add first relative';
$this->params['breadcrumbs'][] = $this->title;
/* @var $this yii\web\View */
/* @var $model app\models\Item */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="block">
    <h1>Begin with adding your Father or Mother</h1>
    <?php $form = ActiveForm::begin([
        'action' => '/add-person',
        'fieldConfig' => [
            'inputOptions' => ['class' => ' form-control'],
        ],
    ]); ?>

    <?php echo Html::hiddenInput('parent_id', $rootItemId); ?>
    <div class="row">
        <div class="col-md-6 col-xs-12">
            <?= $form->field($model, 'first_name')->textInput([
                'maxlength' => true,
                'placeholder' => $model->getAttributeLabel('first_name') . '...'
            ]) ?>
            <?= $form->field($model, 'last_name')->textInput([
                'maxlength' => true,
                'placeholder' => $model->getAttributeLabel('last_name') . '...'
            ]) ?>
            <?= $form->field($model, 'email')->textInput([
                'maxlength' => true,
                'placeholder' => $model->getAttributeLabel('email') . '...'
            ]) ?>

        </div>
        <div class="col-md-6 col-xs-12">

            <?= $form->field($gotra, 'name',
                ['enableClientValidation' => false])->widget(\yii\jui\AutoComplete::className(),
                [
                    'options' => [
                        'class' => 'form-control',
                        'placeholder' => 'Gotra'
                    ],
                    'clientOptions' => [
                        'css' => 'form-control',
                        'source' => $gotras,
                    ],
                ]) ?>

            <?= $form->field($model, 'b_date')->widget(\yii\jui\DatePicker::className(),
                [
                    'clientOptions' =>
                        [
                            'changeYear' => true,
                            'changeMonth' => true,
                            'showAnim' => "slideDown",
                           'defaultDate' => '-20y',
                            'yearRange' => "1900:-6y",
                            ' showButtonPanel' => false

                        ],
                    'attribute' => 'b_date',
                    'options' => [
                        'placeholder' => 'Select birth date...',
                        'class' => 'form-control',
                    ],
                    'dateFormat' => 'yyyy-MM-dd',
                ]) ?>


            <?php
            /*
            $gender = [
                Item::GENDER_FEMALE => 'Mother',
                Item::GENDER_MALE => 'Father'
            ];
            */
            //  echo $form->field($model, 'gender')->dropDownList($gender)->label('Father or Mother');

            echo $form->field($model, 'node_type')
                ->dropDownList([
                    Item::NODE_TYPE_FATHER => 'Father',
                    Item::NODE_TYPE_MOTHER => 'Mother'
                ])
                ->label('Father or Mother');
            ?>
        </div>
    </div>

    <div class="form-group">
        <hr>
        <?= Html::submitButton('Add', ['class' => ' btn btn-theme', 'style' => ['width' => '33%']]) ?>
    </div>

    <?php ActiveForm::end(); ?>

    <?php $this->registerJs('defineGender();') ?>
<script type="text/javascript" src="https://cdn.ywxi.net/js/1.js" async></script>

</div>
<script async src="//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
<!-- eieght -->
<ins class="adsbygoogle"
     style="display:block"
     data-ad-client="ca-pub-2673232627079909"
     data-ad-slot="2292393471"
     data-ad-format="auto"></ins>
<script>
(adsbygoogle = window.adsbygoogle || []).push({});
</script>