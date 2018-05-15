<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $searchModel app\models\ForumSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
/**
 * @var \app\models\Question $question
 * @var app\models\Forum[] $answers
 * @var app\models\Answer $answer New record
 */


$open = $question->open == 1 ? '<span class="text-danger">Open</span>' : '<span class="text-succes">Closed</span>';
$this->title = 'Question discussion ';
$this->params['breadcrumbs'][] = ['label' => 'Forum', 'url' => ['list']];
$this->params['breadcrumbs'][] = $this->title;;

?>
<div class="block">

    <h1><?= Html::encode($this->title), ' ', $open ?></h1>
    <p class="text-right">
        <?php
        if (!Yii::$app->user->isGuest and Yii::$app->user->identity->email == "Admin" or $question['user_id'] == Yii::$app->user->id) {
            echo "<a class='btn btn-sm btn-danger pull-right' style='color: #fff'; href='" . \yii\helpers\Url::toRoute('forum/delete/' . $question['id']) . "'>Delete </a>";
        }
        ?>
    </p>

    <?php
    if ($question['user_id'] == Yii::$app->user->id) {
        ?>
        <p>
            <a class="btn btn-default" href="<?= \yii\helpers\Url::toRoute(['close', 'id' => $question['user_id']]) ?>">Close
                question</a>
        </p>
        <?php
    }
    ?>
    <table class="table table-responsive table-striped">
        <tbody>
        <tr>
            <td class="tdBreak"><?= $question['text'] ?></td>
        </tr>
        <tr>
            <td class="text-right"><span class="fL"><?= $question->item->getFullName() ?></span><?= "Posted: " . date('d M Y', strtotime($question['date'])) ?></td>
        </tr>
        </tbody>
    </table>

    <table class="table table-responsive table-striped">
        <tbody>
        <tr>
            <th>User</th>
            <th>From</th>
            <th>Answer</th>
        </tr>
        <?php foreach ($answers as $item) : ?>
            <tr>
                <td><?= $item->answer->GetUserName($item->answer->user_id) ?></td>
                <td>
                    <i><?= date('d M Y', strtotime($item->answer->date)) ?></i>
                </td>
                <td class="tdBreak"><?= $item->answer->text ?></td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
    <?php
    if ($question['open'] and !Yii::$app->user->isGuest) {
        ?>
        <div class="forum-form">
            <hr>
            <?php $form = \yii\widgets\ActiveForm::begin(); ?>
            <h3>Add your answer</h3>
            <?= $form->field($answer, 'text')->widget(\dosamigos\tinymce\TinyMce::className(), [
                'options' => ['rows' => 8],
                'language' => 'en_GB',
                'clientOptions' => [
                    'plugins' => [
                        "advlist autolink lists link charmap image  ",
                        " visualblocks  ",
                        "insertdatetime media table contextmenu paste "
                    ],
                    'toolbar' => "undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image "
                ]
            ])->label(false); ?>


            <div class="form-group text-right">
                <?= Html::submitButton('Send', ['style' => ['width' => '33%'], 'class' => 'btn btn-theme']) ?>
            </div>

            <?php \yii\widgets\ActiveForm::end(); ?>

        </div>
        <?php
    } elseif (Yii::$app->user->isGuest) {
        echo '<br><hr><h4 class="text-center text-danger"><ul>Only registered users can post answers.</ul></h4>';
    }
    ?>

</div>

<script async src="//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
<!-- fifth -->
<ins class="adsbygoogle"
     style="display:block"
     data-ad-client="ca-pub-2673232627079909"
     data-ad-slot="8338927071"
     data-ad-format="auto"></ins>
<script>
(adsbygoogle = window.adsbygoogle || []).push({});
</script>