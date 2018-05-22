<?php

use yii\helpers\Url;
use yii\helpers\Html;
use yii\helpers\Json;

/** @var $this yii\web\View */
/** @var $searchModel app\models\ProfileSearch */
/** @var $dataProvider yii\data\ActiveDataProvider */
/** @var $normalizedTree string Json */

$this->title = 'Family Tree';
$this->params['breadcrumbs'][] = $this->title;
?>

    <div class="modal fade" id="nodeType">
        <div class="modal-dialog modal-add-entry">
            <div class="modal-content">
                <div class="modal-header" style="padding: 8px;">
                    <p>
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    </p>
                </div>
                <div class="modal-body">
                    <h4 class="text-center">
                        <b>You want to add a person to tree.</b>
                    </h4>
                    <br>
                    <form action="/add-person" method="post" id="full-size-form">
                        <input type="hidden" name="Item[parent_id]" id="parentId" value="<?= isset($first['id']) ?  $first['id'] : null ?>">
                        <input type="hidden" name="<?= Yii::$app->request->csrfParam; ?>"
                               value="<?= Yii::$app->request->csrfToken; ?>"/>
                        <table class="table table-bordered">
                            <tr>
                                <td class="aslabel text-center" colspan="2">
                                    <?= Yii::t('app', 'Who is that person for') ?>
                                    <span id="person_name"><?= isset($first['first_name']) ? $first['first_name']: '' ?></span> ?
                                </td>
                            </tr>
                            <tr>
                                <td colspan="2">
                                    <select class="form-control" name="Item[node_type]" id="node_type_select" required>
                                        <option class="node_type_0" value="">--- Select ---</option>

                                        <?php if (is_null($firstPerson['mother'])): ?>
                                            <option class="node_type_1" value="1">Mother</option>
                                        <?php endif; ?>

                                        <?php if (is_null($firstPerson['father'])): ?>
                                            <option class="node_type_2" value="2">Father</option>
                                        <?php endif; ?>

                                        <?php if (!is_null($firstPerson['mother']) || !is_null($firstPerson['father'])): ?>
                                            <option class="node_type_3" value="3">Sister</option>
                                            <option class="node_type_4" value="4">Brother</option>
                                        <?php endif; ?>

                                        <option class="node_type_5" value="5">Daughter</option>
                                        <option class="node_type_6" value="6">Son</option>
                                        <option class="node_type_7" value="7">Spouse/Partner</option>
                                    </select>
                                </td>
                            </tr>

                            <tr id="marriageDiv">
                                <td colspan="2">
                                    <br/>
                                    <label>
                                        <?= Yii::t('app', 'Check if you want to add child to marriage') ?>
                                    </label>
                                    <input name="Additional[marriage_child]" type="checkbox" id="marriageChild">
                                    <select class="form-control" name="Additional[mariage_with]" id="marriage_type">
                                    </select>
                                </td>
                            </tr>

                            <tr id="siblingsDiv">
                                <td colspan="2">
                                    <br/>
                                    <label>
                                        <?= Yii::t('app', 'Check if you want to add sister/brother to separate parent') ?>
                                    </label>
                                    <input name="Additional[marriage_sibling]" type="checkbox" id="siblingChild">
                                    <select class="form-control" name="Additional[mariage_with]" id="siblings_type">
                                    </select>
                                </td>
                            </tr>

                            <tr id="partnerChildren">
                                <td colspan="2">
                                    <br/>
                                    <label>
                                        <?= Yii::t('app', 'Check if you want to share children for this partner') ?>
                                    </label>
                                    <div id="partnerChildrenContainer"></div>
                                </td>
                            </tr>

                            <tr id="gender">
                                <td class="aslabel">Gender
                                    <i class="pull-right fa fa-question-circle-o" data-toggle="tooltip"
                                       data-placement="top"
                                       title='<?= Yii::t('app', 'You can change gender only for "Partner". Click on gender to change.') ?>'>
                                    </i>
                                    <input class="form-control" type="hidden" id="genderInput" value="0"
                                           name="Item[gender]">
                                </td>
                                <td id="gender_val">
                                <span data-gender="0" onclick="canSetGender(this);" class="gender_option active"
                                      id="female">Female</span>
                                    <span data-gender="1" onclick="canSetGender(this);" class="gender_option "
                                          id="male">Male</span> &nbsp;
                                </td>
                            </tr>
                        </table>

                        <table class="table table-bordered">
                            <tr>
                                <td colspan="2" class="text-center short">Short info (optional)</td>
                            </tr>
                            <tr>
                                <td class="aslabel">First Name</td>
                                <td><input class="form-control" type="text" name="Item[first_name]" required></td>
                            </tr>
                            <tr>
                                <td class="aslabel">Last Name</td>
                                <td><input class="form-control" type="text" name="Item[last_name]"></td>
                            </tr>
                            <tr>
                                <td class="aslabel">Email</td>
                                <td><input class="form-control" type="email" name="Item[email]"></td>
                            </tr>
                        </table>
                        <p class="text-right">
                            <button type="button" class="btn btn-default" data-dismiss="modal" aria-hidden="true">
                                Close
                            </button>
                            <input type="button" class="btn btn-theme" value="Add" id="add_and_stay" onclick="goAndStay()">
                            <input type="hidden" id="stay_treeview" name="stay_treeview" value="0">
                            <input type="submit" class="btn btn-theme" value="Add and complete profile">
                        </p>

                    </form>
                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
        <div class="add-entry-wrapper">
            <div class="add-entry-node">                
                <?= Html::img(Url::toRoute('images/add-curve1.svg'), ['id' => 'curve1', 'class' => 'add-entry-curve']) ?>
                <?= Html::img(Url::toRoute('images/add-curve2.svg'), ['id' => 'curve2', 'class' => 'add-entry-curve']) ?>
                <?= Html::img(Url::toRoute('images/add-curve3.svg'), ['id' => 'curve3', 'class' => 'add-entry-curve']) ?>
                <?= Html::img(Url::toRoute('images/add-curve4.svg'), ['id' => 'curve4', 'class' => 'add-entry-curve']) ?>
                <?= Html::img(Url::toRoute('images/add-curve5.svg'), ['id' => 'curve5', 'class' => 'add-entry-curve']) ?>
                <div class="add-entry-me add-entry-button">
                    <?= Html::img(Url::toRoute('images/man.jpg'), ['class' => 'add-entry-center-image']) ?>
                    <div class="add-entry-text">
                        <div class='add-entry-fname add-entry-text-item'>fist name</div>
                        <div class='add-entry-lname add-entry-text-item'>last name</div>
                        <div class='add-entry-birth add-entry-text-item'>brith</div>
                    </div>
                </div>
                <div class="add-entry-father add-entry-button">
                    <?= Html::img(Url::toRoute('images/man.jpg'), ['class' => 'add-entry-image']) ?>
                    <div class="add-entry-text">Add Father</div>
                </div>
                <div class="add-entry-mother add-entry-button">
                    <?= Html::img(Url::toRoute('images/woman.jpg'), ['class' => 'add-entry-image']) ?>
                    <div class="add-entry-text">Add Mother</div>
                </div>
                <div class="add-entry-brother add-entry-button">
                    <?= Html::img(Url::toRoute('images/man.jpg'), ['class' => 'add-entry-image']) ?>
                    <div class="add-entry-text">Add Brother</div>
                </div>
                <div class="add-entry-sister add-entry-button">
                    <?= Html::img(Url::toRoute('images/woman.jpg'), ['class' => 'add-entry-image']) ?>
                    <div class="add-entry-text">Add Sister</div>
                </div>
                <div class="add-entry-son add-entry-button">
                    <?= Html::img(Url::toRoute('images/man.jpg'), ['class' => 'add-entry-image']) ?>
                    <div class="add-entry-text">Add Son</div>
                </div>
                <div class="add-entry-daughter add-entry-button">
                    <?= Html::img(Url::toRoute('images/woman.jpg'), ['class' => 'add-entry-image']) ?>
                    <div class="add-entry-text">Add Daughter</div>
                </div>
                <div class="add-entry-partner add-entry-button">
                    <?= Html::img(Url::toRoute('images/man.jpg'), ['class' => 'add-entry-image']) ?>
                    <div class="add-entry-text">Add Spouse/Partner</div>
                </div>
                <button type="button" class="add-entry-close close" data-dismiss="modal" aria-hidden="true">×</button>

            </div>
        </div>
    </div><!-- /.modal -->

    <div class="block noPadVert">
        <div class="row">

            <div id="chartArea" class="col-xs-9 noPad">
                <div id="chart">
                    <!-- here will be chart -->
                </div>

                <button class="btn btn-theme toggleArea first" onclick="toggleArea();">
                    <i class="fa fa-lg fa-exchange" aria-hidden="true"></i>
                </button>

            </div>

            <div id="profileArea" class="col-xs-3 noPad">
                <table id="profile" class="table  profileCard table-responsive">
                    <caption><?= Yii::t('app', 'Profile') ?></caption>
                    <tbody>
                    <tr>
                        <td colspan="2" class="text-center">
                            <img style="margin: auto;" id="image" src="<?= isset($first['image']) ? $first['image'] : ''; ?>"
                                 width="150" height="200" class="img-responsive" alt="">
                        </td>
                    </tr>
                    <tr>
                        <td><?= Yii::t('app', 'First name') ?></td>
                        <td id="f_name"><?= isset($first['first_name']) ? $first['first_name'] : '' ?></td>
                    </tr>
                    <tr>
                        <td><?= Yii::t('app', 'Last name') ?></td>
                        <td id="l_name"><?= isset($first['last_name']) ? $first['last_name'] : '' ?></td>
                    </tr>
                    <tr>
                        <td><?= Yii::t('app', 'Birth date') ?></td>
                        <td id="birth"><?= isset($first['birth']) ? $first['birth'] : null; ?></td>
                    </tr>
                    <?php $showDeath = !is_null($first['death']) ? '' : 'display: none'; ?>
                    <tr id="deathTr" style="<?= $showDeath; ?>">
                        <td><?= Yii::t('app', 'Death date') ?></td>
                        <td id="death"><?= isset($first['death']) ?  $first['death'] : null; ?></td>
                    </tr>
                    <tr id="genderTr">
                        <td></td>
                        <td id="gender"><?= isset($first['class']) ? $first['class'] : '' ?></td>
                    </tr>

                <?php if($canEdit){ ?>
                    <tr>
                        <td colspan="2">
                            <?php echo Html::a(
                                Yii::t('app', 'View profile'),
                                Url::toRoute('/view/' . (isset($first['id']) ? $first['id'] : null)),
                                [
                                    'id' => 'view',
                                    'class' => 'btn w100 btn-theme'
                                ]
                            ) ?>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="2">
                            <?php echo Html::a(
                                Yii::t('app', 'Add person'),
                                '#',
                                [
                                    'id' => 'add',
                                    'data-toggle' => 'modal',
                                    'data-target' => '#nodeType',
                                    'class' => 'btn w100 btn-theme'
                                ]
                            ) ?>
                        </td>
                    </tr>
                <?php } ?>

				<?php if( isset($_SERVER['HTTP_REFERER']) && strpos($_SERVER['HTTP_REFERER'], 'profile/search') !== false ) { ?>
                    <tr>
                        <td colspan="2">
                            <?php echo Html::a('Back to search results', $_SERVER['HTTP_REFERER'], ['class' => 'btn w100 btn-theme'] ); ?>
                        </td>
                    </tr>
				<?php } ?>

                    </tbody>
                </table>
            </div>
        </div>

        <div id="graph" style="display: none"></div>
    </div>

    <div class="modal fade" tabindex="-1" role="dialog" id="print-modal">
        <div class="modal-dialog" role="document" style="width: 82vw; height: 80vh;">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">Print Your Family Tree</h4>
                </div>
                <div class="modal-body">
                    <div id="print-please-wait">
                        Please wait a few moments while we generate a PDF which you can use to print and share your family tree.
                    </div>

                    <div id="print-ready">
                        <p class="bg-success" style="padding: 8px;">
                            The PDF of your family tree has been generated!
                            <a href="javascript:void(0);" id="print-download-link" target="_blank">Click here to download the PDF of your family tree.</a>
                        </p>
                        <p>
                            Use the zoom and movement tools on the family tree page in order to create a clearer or
                            shifted printout.
                        </p>
                        <p>
                            You can see a preview of the PDF below which can also be used to print or save your family tree:
                        </p>
                        <p>
                            (Your browser may not support the preview. If not, just use the download link above.)
                        </p>
                        <iframe id="print-iframe" style="width: 80vw; height: 50vh;"></iframe>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->

<?php
$this->registerJs('treeData =' . $normalizedTree);
$this->registerJs('hasPersonParents =' . $hasPersonParents);
$this->registerJs('authUserId ="' . $current_node_id . '"');
$this->registerJs('currentRootId ="' . $current_node_id . '"');
$this->registerJs('printTree(' . $normalizedTree . ',' . $popover . ');');
$this->registerJs('treeNodes =' . $treeNodes);
$this->registerJs('rootUserName = ' . Json::encode($firstPerson['name']) . '.replace(/\\s+/g, " ");');