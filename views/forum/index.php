<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\ArrayHelper;
use yii\widgets\ListView;
use yii\widgets\Pjax;
use yii\widgets\LinkPager;


/* @var $this yii\web\View */
/* @var array $models */
/* @var array $pages */

$this->title = 'Forum';
$this->params['breadcrumbs'][] = $this->title;
$this->registerMetaTag([
'name'=> 'title',
'content'=>'Kulbeli Discussion Forums |One Stop forums for Gotra discussion, Caste, Family search discussion
'
]);
$this->registerMetaTag([
     'name' => 'description',
     'content' => 'India Forums for Gotra discussion, Caste, Family search, Kuldevi, Kuldevta, Veda, Religious,history of India discussion
,History, Culture, Articles, Columns, Analysis Research Papers. Facts about India, Yoga Meditation Ayurveda Indian History, Culture, Religion. Mystery Diversity Rich information resource, global Indian community Asia. Discussions, Comments Archive Library. Indus Valley Culture. Religious Indians, History Invasion, Hindu, Hinduism, Christianity, Islam'
]);

$this->registerMetaTag([
     'name' => 'keywords',
     'content' => 'List of gotra, Gotra,gotras, Atri, Jamadagni, Vishvamitra, Brahmin, List of Brahmin gotra, List of Dhangar, clans in India, Hinduism, Agastya, Bhargava, Rajput Gotra, caste, search engine, databases, Web sites, websites, archives, history discussion, gotra discussion, gotra forum, India gotra forum,ancestry,family tree,genealogy,family history,free family tree,my family tree,find my family,family tree chart,ancestors,how to make a family tree,my ancestry,find your ancestors,make a family tree,genealogist,family lineage,family tree websites,family heritage,find family,family tree design,create family tree,family tree diagram,how to make family tree,genealogical tree,the family tree,family tree online,online family tree,family finder,a family tree,make family tree,tree family,family tree format,family origin,family name,family tree project,family tree examples,create website,website creator,family details,census records india,family search,brahmin,own website,my family lines,historical records'
]);


?>

<div class="block">
	<div class="row">
		<div class="col-xs-12 col-md-2">
            <h1>Search In Forum</h1>
			<?php echo $this->render('_search', ['model' => $searchModel]); ?>
		</div>
		<div class="col-xs-12 col-md-10">
			<h1<?php if (!Yii::$app->user->isGuest) { ?> style="padding-bottom: 10px;"<?php } ?>>Forum
			<?php if (!Yii::$app->user->isGuest) { ?>
				<a class="btn btn-default" href="<?= \yii\helpers\Url::toRoute('question') ?>">Add question</a>
			<?php } ?>
			</h1>
			<?=
			GridView::widget([
				'dataProvider' => $dataProvider,
				'columns' => [
					[
						'attribute' => 'Question',
						'format' => 'html',
						'value' => function($model) {
							return $model['text'];
						}
					],
					[
						'attribute' => 'Status',
						'format' => 'html',
						'value' => function($model) {
							$open = $model['open'] == 1 ? '<span class="text-danger"><b>Open</b></span>' : '<span class="text-success" ><b>Closed</b></span>';
							return $open;
						}
					],
					[
						'label' => 'User',
						'value' => function(\app\models\Question $model) {
							return $model->item->getFullName();
						}
					],
					[
						'attribute' => 'Created',
						'format' => 'html',
						'value' => function($model) {
							return date('d M Y', strtotime($model['date']));
						}
					],
					[
						'attribute' => 'Last answer',
						'format' => 'html',
						'value' => function($model) {
							return Yii::$app->main->getLastAnswerDate($model['id']);
						}
					],
					[
						'attribute' => 'Total answers',
						'format' => 'html',
						'value' => function($model) {
							return Yii::$app->main->getCountAnswers($model['id']);
						}
					],
					[
						'format' => 'html',
						'value' => function($model) {
							return "<a class='btn btn-theme btn-xs' href='" . \yii\helpers\Url::to('discuss/' . $model['id']) . "'>View</a>";
						}
					],
				],
			]);
			?>
		</div>
	</div>

	<?php /*
    <table class="table table-responsive table-striped">
        <tr>
            <th>Question</th>
            <th>Status</th>
            <th>Created</th>
            <th>Last answer</th>
            <th>Total answers</th>
            <th></th>
        </tr>
        <?php
        if (!$models) {
            echo '<tr><td colspan="2"> Nothing to show</td></tr>';
        }
        foreach ($models as $model) {
            $open = $model['open'] == 1 ? '<span class="text-danger"><b>Open</b></span>' : '<span class="text-success" ><b>Closed</b></span>';
            echo "<td>" . $model['text'] . '</td>';
            echo "<td style='width: 70px;' >" . $open . "</td>";
            echo "<td>" . date('d M Y', strtotime($model['date'])) . '</td>';
            echo "<td style='width: 90px;' class='text-right'>" . Yii::$app->main->getLastAnswerDate($model['id']) . '</td>';
            echo "<td style='width: 100px;' class='text-right'>" . Yii::$app->main->getCountAnswers($model['id']) . '</td>';
            echo "<td style='width: 80px;' class='text-right'><a class='btn btn-theme btn-xs' href='" . \yii\helpers\Url::to('discuss/' . $model['id']) . "'>View</a></td></tr>";
        }
        ?>
    </table>

    <div class="text-center">
        <?php
        // display pagination
        echo \yii\widgets\LinkPager::widget([
            'pagination' => $pages,
        ]);
        ?>

    </div>
	*/ ?>
</div>
<script async src="//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
<!-- seventh -->
<ins class="adsbygoogle"
     style="display:block"
     data-ad-client="ca-pub-2673232627079909"
     data-ad-slot="9815660279"
     data-ad-format="auto"></ins>
<script>
(adsbygoogle = window.adsbygoogle || []).push({});
</script>