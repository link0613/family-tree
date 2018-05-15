<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\models\Item;

$this->title = 'Profile';
$this->params['breadcrumbs'][] = $this->title;

/* @var $this yii\web\View */
/* @var $model Item */
/* @var $form yii\widgets\ActiveForm */
?>
    <div class="block">

        <div class="row">

            <div class="col-md-3 col-xs-12">
                <?= $this->render('profile_menu'); ?>
            </div>

            <div class="col-md-9 col-xs-12">
                <h1><?= Html::encode($this->title), ' <span class="small">', $model->first_name, ' ', $model->last_name, '</span>' ?></h1>
                <?php
                $this->registerJs('
                $(".table.table-bordered.menu-table .fa:eq(0)").addClass("active");
            ');
                $key2class = ['success' => 'info','error' => 'danger'];
                foreach (Yii::$app->session->getAllFlashes() as $key => $message) {
	                if ( is_array($message) ) {
		                $message = join('<br>',$message);
	                }
	                if ( isset($key2class[$key]) ) {
		                $key = $key2class[$key];
	                }
	                echo \yii\bootstrap\Alert::widget([
		                'options' => [
			                'class' => "alert-$key",
		                ],
		                'body' => $message
	                ]);
                }
                ?>
                <?php $form = ActiveForm::begin([
                    'action' => '/view/' . $model->id,
                    'fieldConfig' => [
                        'inputOptions' => ['class' => ' form-control'],
                    ],
                ]); ?>

                <?php
                if (isset($g)) {
                    $gender = $g == 1 ? [1 => 'Male'] : [0 => 'Female'];
                } else {
                    $gender = [
                        0 => 'Female',
                        1 => 'Male'
                    ];
                }

                $married = [
                    0 => 'Unmarried',
                    1 => 'Married'
                ];

                $privacyStatuses = [
                    Item::PRIVACY_OPEN => 'Public',
                    Item::PRIVACY_CLOSED => 'Private'
                ];
                ?>

		<div class="row">
			<div class="col-xs-4">
                <?= $form->field($model, 'first_name')->textInput([
                    'maxlength' => true,
                    'placeholder' => $model->getAttributeLabel('first_name') . '...'
                ]) ?>
			</div>

			<div class="col-xs-4">
                <?= $form->field($model, 'middle_name')->textInput([
                    'maxlength' => true,
                    'placeholder' => $model->getAttributeLabel('middle_name') . '...'
                ]) ?>
			</div>

			<div class="col-xs-4">
                <?= $form->field($model, 'last_name')->textInput([
                    'maxlength' => true,
                    'placeholder' => $model->getAttributeLabel('last_name') . '...'
                ]) ?>
			</div>
		</div>

		<div class="row">
			<div class="col-xs-4">
                <?= $form->field($model, 'maiden_name')->textInput([
                    'maxlength' => true,
                    'placeholder' => $model->getAttributeLabel('maiden_name') . '...'
                ]) ?>
			</div>

			<div class="col-xs-4">
                <?= $form->field($model, 'married')->dropDownList($married) ?>
			</div>

			<div class="col-xs-4">
                <div class="form-group field-item-privacy">
                    <label class="control-label" for="item-privacy"><?= $model->getAttributeLabel('privacy') ?></label>
                    <p>
                        <a
                                id="item-privacy"
                                class="btn btn-default"
                                data-toggle="collapse"
                                onclick="privacyToggle(this);">
                            <?= $model->privacy ? 'Private' : 'Public' ?>
                        </a>
                    </p>

                    <?= $form->field($model, 'privacy')->hiddenInput(['id' => 'privacy'])->label(false) ?>

                </div>
			</div>
		</div>

		<div class="row">
			<div class="col-xs-4">
                <?= $form->field($model, 'email')->textInput([
                    'maxlength' => true,
                    'placeholder' => $model->getAttributeLabel('email') . '...'
                ]) ?>
			</div>

			<div class="col-xs-4">
                <?= $form->field($model, 'occupation')->textInput([
                    'maxlength' => true,
                    'placeholder' => $model->getAttributeLabel('occupation') . '...'
                ]) ?>
			</div>

			<div class="col-xs-4">
                <?= $form->field($gotra, 'name',
                    ['enableClientValidation' => false])->widget(\yii\jui\AutoComplete::className(),
                    [
                        'options' => [
                            'class' => 'form-control',
                            'placeholder' => 'Gotra'
                        ],
                        'clientOptions' => [
                            'css' => 'form-control',
                            'source' => $gotras
                        ],
                    ]) ?>
			</div>
		</div>

		<div class="row">
			<div class="col-xs-4">
                <?= $form->field($model, 'b_country')->textInput([
                    'maxlength' => true,
                    'placeholder' => $model->getAttributeLabel('b_country') . '...'
                ]) ?>
			</div>

			<div class="col-xs-4">
                <?= $form->field($model, 'b_city')->textInput([
                    'maxlength' => true,
                    'placeholder' => $model->getAttributeLabel('b_city') . '...'
                ]) ?>
			</div>

			<div class="col-xs-4">
                <?= $form->field($model, 'b_date')->widget(\yii\jui\DatePicker::className(),
                    [
                        'clientOptions' =>
                            [
                                'changeYear' => true,
                                'changeMonth' => true,
                                'showAnim' => "slideDown",
                                'defaultDate' => '-20y',
                                'yearRange' => "-400y:+0y",
                                'showButtonPanel' => false
                            ],
                        'attribute' => 'b_date',
                        'options' => [
                            'placeholder' => 'Select birth date...',
                            'class' => 'form-control',
                        ],
                        'dateFormat' => 'yyyy-MM-dd',
                    ]) ?>
			</div>
		</div>

                <?php if ($model->root != 1): ?>

                    <?php
                    //  $form->field($model, 'dead')->hiddenInput(['id' => 'isDead'])->label(false)
                    ?>

                    <div class="form-group">
                        <p>Person is <a class="btn btn-default" data-toggle="collapse" data-target="#dead"
                                        onclick="deathToggle(this);"><?= $model->death_id ? 'Dead' : 'Alive' ?></a>
                        </p>
                    </div>

                    <div id="dead" class="collapse <?php echo $model->death_id ? 'in' : '' ?>">

		<div class="row">
			<div class="col-xs-4">
                        <?= $form->field($death, 'country')->textInput([
                            'maxlength' => true,
                            'placeholder' => $death->getAttributeLabel('country') . '...'
                        ]) ?>
			</div>

			<div class="col-xs-4">
                        <?= $form->field($death, 'city')->textInput([
                            'maxlength' => true,
                            'placeholder' => $death->getAttributeLabel('city') . '...'
                        ]) ?>
			</div>

			<div class="col-xs-4">
                        <?= $form->field($death, 'date')->widget(\yii\jui\DatePicker::className(),
                            [
                                'clientOptions' =>
                                    [
                                        'changeYear' => true,
                                        'changeMonth' => true,
                                        'showAnim' => "slideDown",
                                        'yearRange' => "-400y:+0y",
                                        ' showButtonPanel' => false

                                    ],
                                'attribute' => 'b_date',
                                'options' => [
                                    'placeholder' => 'Select death date...',
                                    'class' => 'form-control',
                                ],
                                'dateFormat' => 'yyyy-MM-dd',
                            ]) ?>
			</div>
		</div>

		<div class="row">
			<div class="col-xs-4">
                        <?= $form->field($death, 'reason')->textInput([
                            'maxlength' => true,
                            'placeholder' => $death->getAttributeLabel('reason') . '...'
                        ]) ?>
			</div>
			<div class="col-xs-4">
                        <?= $form->field($death, 'bury_date')->widget(\yii\jui\DatePicker::className(),
                            [
                                'clientOptions' =>
                                    [
                                        'changeYear' => true,
                                        'changeMonth' => true,
                                        'showAnim' => "slideDown",
                                        'yearRange' => "-400y:+0y",
                                        ' showButtonPanel' => false
                                    ],
                                'attribute' => 'b_date',
                                'options' => [
                                    'placeholder' => 'Select bury date...',
                                    'class' => 'form-control',
                                ],
                                'dateFormat' => 'yyyy-MM-dd',
                            ]) ?>
			</div>
			<div class="col-xs-4">
                        <?= $form->field($death, 'bury_country')->textInput([
                            'maxlength' => true,
                            'placeholder' => $death->getAttributeLabel('bury_country') . '...'
                        ]) ?>
			</div>
		</div>

		<div class="row">
			<div class="col-xs-4">
                        <?= $form->field($death, 'bury_city')->textInput([
                            'maxlength' => true,
                            'placeholder' => $death->getAttributeLabel('bury_city') . '...'
                        ]) ?>
			</div>
			<div class="col-xs-4">
                        <?= $form->field($death, 'cemetery')->textInput([
                            'maxlength' => true,
                            'placeholder' => $death->getAttributeLabel('cemetery') . '...'
                        ]) ?>
			</div>
		</div>

                    </div>

                <?php endif; ?>

                <?= Html::hiddenInput('returnToTree', null, ['id' => 'returnToTree']); ?>
                <?= Html::hiddenInput('addNewNode', null, ['id' => 'addNewNode']); ?>

                <div class="row">
                    <div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xs-12 text-right">
                        <?= Html::submitButton('Save', ['class' => 'btn btn-theme']) ?>

                        <?= Html::submitButton('Save & Add New Member', [
                            'class' => 'btn btn-theme',
                            'onclick' => 'setAddNewNode()'
                        ]) ?>

                        <?= Html::submitButton('Save & Return to Tree', [
                            'class' => 'btn btn-theme',
                            'onclick' => 'setReturnTreeId()'
                        ]) ?>
                    </div>
                </div>

                <?php ActiveForm::end(); ?>
            </div>
        </div>

    </div>

<?php $this->registerJs('defineGender();'); ?>
<script async src="//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
<!-- second ad -->
<ins class="adsbygoogle"
     style="display:block"
     data-ad-client="ca-pub-2673232627079909"
     data-ad-slot="4085354279"
     data-ad-format="auto"></ins>
<script>
(adsbygoogle = window.adsbygoogle || []).push({});
</script>
