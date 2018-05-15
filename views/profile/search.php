<?php

use yii\helpers\Html;
use yii\helpers\Url;
use app\models\Item;
use yii\grid\GridView;
use \yii\bootstrap\Modal;
use yii\bootstrap\ActiveForm;

/* @var $this yii\web\View */
/* @var $searchModel app\models\ItemSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
/* @var $model Item */

$this->title = 'Search for users';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="block">
    <div class="row">
        <div class="col-xs-12 col-md-2">
            <h1><?= Html::encode($this->title) ?></h1>
            <?php echo $this->render('_profileSearch', ['model' => $searchModel]); ?>
        </div>
        <div class="col-xs-12 col-md-10">
            <div id="result">
                <h1>Search results</h1>

                <?= GridView::widget([
                    'dataProvider' => $dataProvider,
                    //'filterModel' => $searchModel,
                    'tableOptions' => ['class' => 'table table-responsive table-striped'],
                    'columns' => [
                        [
                            'attribute' => 'image',
                            'format' => 'raw',
                            'value' => function ($model) {
                                $image = '';
                                if (!is_null($model->getImage())) {
                                    $image = Html::img(
                                        Url::to('images/profile/' . $model->getImage(), true),
                                        ['class' => 'profileImage']
                                    );
                                }
                                return $image;
                            }
                        ],
                        'first_name',
                        'last_name',
                        [
                            'attribute' => 'b_date',
                            'value' => function ($model) {
                                return !is_null($model->getBirthDate()) ? date('Y',
                                    strtotime($model->getBirthDate())) : null;
                            }
                        ],
                        'b_city',
                        [
                            'attribute' => 'gotra',
                            'value' => function ($model) {
                                return (isset($model->gotra) && !is_null($model->gotra)) ? $model->gotra->name : null;
                            }
                        ],
                        'occupation',
                        [
                            'label' => Yii::t('app', 'Father'),
                            'value' => function ($model) {
                                $modelFather = $model->getFather()->one();
                                return !is_null($modelFather) ? $modelFather->getFullName() : null;
                            }
                        ],
                        [
                            'label' => Yii::t('app', 'Mother'),
                            'value' => function ($model) {
                                /** @var Item $modelMother */
                                $modelMother = $model->getMother()->one();
                                return !is_null($modelMother) ? $modelMother->getFullName() : null;
                            }
                        ],
                        [
                            'label' => 'Children & Siblings',
                            'format' => 'raw',
                            'value' => function ($model) {
                                /** @var Item $model */
                                /** @var Item[] $children */
                                /** @var Item[] $siblings */

                                $response = '';

                                $children = [];
                                if ($model->getGender() == Item::GENDER_MALE) {
                                    $children = $model->getFatherChildren()->all();
                                } elseif ($model->getGender() == Item::GENDER_FEMALE) {
                                    $children = $model->getMotherChildren()->all();
                                }

                                if (!empty($children)) {
                                    $response .= "<strong>Children :</strong><br />";
                                    foreach ($children as $child) {
                                        $response .= $child->getFullName() . "<br />";
                                    }
                                }

                                // siblings
                                $siblings = $model->getSiblings();

                                if (!empty($siblings)) {
                                    $response .= "<strong>Siblings :</strong><br />";
                                    foreach ($siblings as $sibling) {
                                        $response .= $sibling->getFullName() . "<br />";
                                    }
                                }

                                return $response;
                            }
                        ],
//comment out by David due to security issues on birth date in tree nodes
						[
							'class' => 'yii\grid\ActionColumn',
							'header' => Yii::t('app', 'View'),
							'headerOptions' => ['width' => '80'],
							'template' => '{view}',
							'buttons' => [
							   'view' => function ($url = '', $model) {
									$res = '';
									if ($model->getPrivacy() == Item::PRIVACY_OPEN) {
										$res .= Html::a(
											Yii::t('app', 'View'),
											Url::toRoute(['tree/chart', 'id' => $model->getId()]),
											  ['class' => 'btn btn-theme btn-xs']
										  );
									} else {
										$res .= 'Private';
									}
									$res .= '<br />';
									if( $model->email )
										$res .= '<a class="btn btn-warning btn-xs" data-toggle="modal" data-target="#profileContactModal" onclick="$(\'#profilecontactform-id\').val(' . $model->getId() . ');">Contact</a>';
									else
										$res .= 'No email';
									return $res;
								}
							],
						],
                    ],
                ]); ?>
            </div>
        </div>
    </div>
</div>

<?php
Modal::begin([
    'header' => '<h3><i class="fa fa-lg fa-user"></i>&nbsp; Profile Contact form </h3>',
    'id' => 'profileContactModal',
    'size' => Modal::SIZE_LARGE,
]);
?>
<div class="profile-contact">

	<?php $form = ActiveForm::begin([
		'id' => 'profilecontact-form',
	]); ?>

	<?= $form->field($profilecontact, 'id')->hiddenInput()->label(false); ?>
	<?= $form->field($profilecontact, 'subject')->textInput(); ?>
	<?= $form->field($profilecontact, 'message')->textArea(); ?>

	<div class="form-group">
		<?= Html::submitButton('Send', [
			'class' => 'btn btn-primary btn-theme',
			'id' => 'ajaxSubmit',
			'name' => 'contact-button',
			'style' => ['width' => '100%']
		]); ?>
	</div>

	<?php ActiveForm::end(); ?>
</div>
<?php
Modal::end();
?>
