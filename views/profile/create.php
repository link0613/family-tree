<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\models\Item;

/* @var $this yii\web\View */
/* @var $model app\models\Item */
/* @var $form yii\widgets\ActiveForm */

$this->title = 'Add new person';
$this->params['breadcrumbs'][] = $this->title;
?>

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

$nodeTypes = [
    Item::NODE_TYPE_MOTHER => 'Mother',
    Item::NODE_TYPE_FATHER => 'Father',
    Item::NODE_TYPE_SISTER => 'Sister',
    Item::NODE_TYPE_BROTHER => 'Brother',
    Item::NODE_TYPE_DAUGHTER => 'Daughter',
    Item::NODE_TYPE_SON => 'Son',
    Item::NODE_TYPE_SPOUSE_PARTNER => 'Spouse/Partner'
];

$genders = [
    Item::GENDER_MALE => 'Male',
    Item::GENDER_FEMALE => 'Female'
];

$this->registerJs('userItemsData = ' . \yii\helpers\Json::encode($userItemsData));
?>


    <div class="block">

        <div class="row">

            <div class="col-md-3 col-xs-12">
                <?= $this->render('profile_menu'); ?>
            </div>
            <div class="col-md-9 col-xs-12">
                <h1>
                    <?= Html::encode($this->title), ' <span class="small">', $model->getFirstName(), ' ', $model->getLastName(), '</span>' ?>
                </h1>
                <?php
                $this->registerJs('
                $(".table.table-bordered.menu-table .fa:eq(0)").addClass("active");
            ');
                if (Yii::$app->session->getFlash('success')) {
                    echo \yii\bootstrap\Alert::widget([
                        'options' => [
                            'class' => 'alert-info',
                        ],
                        'body' => Yii::$app->session->getFlash('success')
                    ]);
                }
                ?>

                <?php $form = ActiveForm::begin([
                    'action' => \yii\helpers\Url::to('view'),
                    'fieldConfig' => [
                        'inputOptions' => ['class' => ' form-control'],
                    ],
                ]); ?>

		<div class="row">
			<div class="col-xs-4">
                <?= $form->field($model, 'parent_id')->dropDownList($userItems, [
                    'prompt' => '--- Select person ---',
                    'id' => 'createParentId',
                    'required' => true
                ])->label('Related person');
                ?>
			</div>

			<div class="col-xs-4">
                <?= $form->field($model, 'node_type')->dropDownList($nodeTypes, [
                    'prompt' => '--- Select relation ---',
                    'id' => 'createNodeTypeSelect',
                    'required' => true
                ])->label('Relation type');
                ?>
			</div>

			<div class="col-xs-4">
                <?= $form->field($model, 'gender')->dropDownList($genders, [
                    'id' => 'newPersonGender'
                ])->label('Gender'); ?>
			</div>
		</div>

                <div id="createMarriageDiv" style="display: none">
                    <br/>
                    <label>Check if you want to add child to marriage</label>
                    <input name="Additional[marriage_child]" type="checkbox" id="createMarriageChild">
                    <select class="form-control" name="Additional[mariage_with]" id="createMarriageType"></select>
                </div>

                <div id="createSiblingsDiv" style="display: none">
                    <br/>
                    <label>Check if you want to add sister/brother to separate parent</label>
                    <input name="Additional[marriage_sibling]" type="checkbox" id="createSiblingChild">
                    <select class="form-control" name="Additional[mariage_with]" id="createSiblingsType"></select>
                </div>

                <div id="createPartnerChildren" style="display: none">
                    <br/>
                    <label>
                        <?= Yii::t('app', 'Check if you want to share children for this partner') ?>
                    </label>
                    <div id="createPartnerChildrenContainer"></div>
                </div>

                <hr/>

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
                <?= $form->field($model, 'privacy')->dropDownList($privacyStatuses) ?>
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
<script type="text/javascript" src="https://cdn.ywxi.net/js/1.js" async></script>

    </div>

<?php
//  $this->registerJs('defineGender();');
?>