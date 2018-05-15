<?php

use yii\helpers\Html;
use yii\widgets\ListView;
use yii\widgets\Pjax;
use \yii\widgets\LinkPager;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\PostSearch */
/* @var $models app\models\Post */
/**
 * @var array $pages
 */

$this->title = 'Blog';
$this->params['breadcrumbs'][] = $this->title;

$this->registerMetaTag([
     'name' => 'description',
     'content' => 'Kulbeli Blog |to create family tree. Know about Veda, Religious, history of India, Culture, Analysis Research Papers. Facts about India'
]);

$this->registerMetaTag([
     'name' => 'keywords',
     'content' => 'rajput history, history of rajputs, rajput caste, rajput dynasty, rajputs history, history of rajput, origin of rajputs, rathore history, parmar rajput history, rajput kingdom, rajputs in india, rajput vansh, rajput rulers, rajput empire, history of rajputs in india,Rajputs,Rajputana,Rajput States,Rajput Thikanas,Rajput Princely States,Rajput Thikanas,Rajput Jagirs,Kshatriya,Kshatriya Samaj,Kshatriyas,Thakur,Bana,Baisa,Rathore Dynasty Tree,Rathore Vanshavali,Rathore Vansawali,Tanwar Dynasty Tree,Tanwar Vanshavali,Tanwar Vansawali
Rathore,Sisodia,Parmar,Chauhan,Kachwaha,Shekhawat,Deora Chauhan,Tanwar,Jadeja,Jhala,Bhati,Wala,Baghela,Bundela,Chandel,Hada Chauhan,Khichi Chauhan,Suryavanshi,Solanki,Katoch,Gohil,Parihar,Raj Gond,Bisen,Bhonsle,Chandrabansi,Jadon,Ghorpade,Bais,Bhadoria,Janwar,Raksel,Bhanj,Songara Chauhan,Bachgoti,Dodiya Rajput,Bhumihar,Jamwal,Gaur,Kathi Kshatriya,Badgujjar,Gaharwar,Surajbansi,Chandra,Pal,Sombanshi,Chavda,Naagvanshi,Maharaulji,Sen,Khandavala,Naruka,Ghorewaha,Tung,Balla,Dabhi,Naga,Sikarwar,Gangavansi,Kathi,Raghuvansi,Holkar,Mushana,Sengar,Bhilala,Ganga,Badani,Gor,Scindia,Yaduvanshi,Gaekwad,Jethwa,Amethia,Khachar,Rana,Wadiyar,Bandalghoti,Narayan,Bhuyavamsha,Thorat,Gangabasi,Kanhpuria,Raghuvanshi,Thikana,Princely State,Jagir,Zamindari,Taluk,Istimrari,Zaildari,Ismatdari,Girasia,Bhumiate,Rajputs,Rajputana,Rajput States,Rajput Thikanas,Rajput Princely States,Rajput Thikanas,Rajput Jagirs,Kshatriya,Kshatriya Samaj,Kshatriyas,Thakur,Bana,Baisa,Rathore Dynasty Tree,Rathore Vanshavali,Rathore Vansawali,Tanwar Dynasty Tree,Tanwar Vanshavali,Tanwar Vansawali,rajput,rajput history,rajput caste,blog websites,rajput warrior,writing websites,rajput history in hindi,rajput caste list,yadav caste,rajput in india,chauhan caste,rajput caste surnames list,rajput dynasty,suryavanshi caste,rajput women,rajasthan rajput,family tree websites,rajput caste category,the rajputs,rajput marriage,rajput indian,rathore rajput history in hindi,rajasthani rajput,muslim rajput,kshatriya surnames,rajput history in hindi language,rajputs surnames,rajput names,solanki rajput history in hindi,thakur caste gotra,rajput caste names,indian rajput,rajput kings,rajput gotra list,rajput gotra list in hindi,royal rajput,website india,website form,rajput marriage rules,chauhan rajput history in hindi,suryavanshi rajput,bhati rajput history in hindi,rajput rulers,parmar rajput history in hindi,agnikula kshatriya caste history,rajputs in indian history,chauhan rajput,hindu rajput,bais rajput,rajput empire,rajput gotra,panwar rajput history in hindi,rajput kingdom,rajasthan rajput history in hindi,rajput community,chandravanshi history,all rajput caste,rajput family,rajput surnames list,agnikula kshatriya caste surnames,naruka rajput history in hindi,agnikula kshatriya history,rajput caste in up,rajput rajasthan,chandravanshi rajput,rathore history,rajput group,rajput muslim,rathore caste,rajput chauhan,royal rajput names,rajput culture,rathore rajput history,list gotra kuldevi,chauhan caste history in hindi,kshatriya rajput,bhati rajput history,history of rajput,history in india,himachali rajput caste list,rajput vansh,chauhan rajput family tree,rawat rajput gotra,history about india,rajputs of rajasthan,rajput vanshavali,parmar rajput,family tree india,rajput history in gujarati,kashyap rajput caste,rajput gotra in hindi,rajput age,rajput language,rajput kshatriya,rajput in bihar,singh rajput caste,list of rajput surnames,websites in india,bhati rajput gotra,kashyap rajput gotra list,chandravanshi caste history,navigation india,rajput kings names,pakistani rajput,gotra of rajput kshatriya,rajput in rajasthan,chauhan caste in up,rajput king prithviraj chauhan,punjabi rajput surnames,ratan rajput marriage,rajput story,raj rajput,chandravanshi caste,rajput kings names list,india navigation,rajput in hindi,chauhan caste gotra,all rajput gotra list,rajput stories in hindi,chauhan history,punjabi rajput,gotra,maratha rajput caste,parmar rajput history in gujarati,gotra list,suryavanshi caste list,rajput history in rajasthan hindi,janjua caste,rajputana culture of india,same gotra marriage solution,rajput clans,history of the india,sisodia rajput history in hindi,parihar rajput history in hindi,panwar rajput history,bengali rajput surnames,gotra of rajput,rathod history,gaur brahmin gotra list,suryavanshi kshatriya,solanki rajput full history in hindi,royal rajput name list,rajput kings history,zala rajput history,kachwaha rajput history in hindi,rajput in up,prithviraj chauhan family tree,yadav caste gotra,kachwaha rajput,brahmin gotra,chauhan family,india of history,yadav gotra,same gotra marriage solution in hindi,chauhan rajput vanshavali,rajput vansh history in hindi,rajput states,brahmin gotra list in hindi,rajput origin,solanki rajput history in gujarati,rajput forts,solanki rajput history,mewar rajputana history in hindi,teli caste gotra,telugu brahmin surnames and gotras,rajput period,name of rajput kings,brahmin surnames and gotras,brahman samaj gotra,soni samaj gotra list,rajput names list,36 clans of rajput,surnames of rajputs,kashyap gotra caste,valmiki caste gotra,kashyap gotra surnames,same gotra marriage,jatav gotra list,suryavanshi rajput gotra,rajput wedding,saryuparin brahmin gotra list,kashyap gotra belongs to which caste,jatav gotra,famous rajput personalities,indian rights,gurjar gotra,gohil rajput history,parihar rajput history,jatav caste gotra,nepali caste and gotra,conflict between jats and rajputs,rajput caste comes under which category,mala caste gotra,sisodia rajput kuldevi,vaishnav samaj gotra list,kashyap surname belongs to which caste,somvanshi rajput,gotra in hindi,chauhan vansh history in hindi,brahmin gotra list,vishwakarma caste gotras,agnikula kshatriya surnames and gotras,thakur gotra,history india,prajapati caste gotra,brahmin gotras and surnames,marriage in same gotra,kaushal gotra,family tree,list of rajput,bhardwaj gotra surnames,shiva gotra,khatri gotra,himachali rajput surnames,kaushik gotra,gowda caste gotra,same gotra marriage effect,atri gotra,gotra of vishwakarma,hindu gotra,rajput history photos,scheduled caste gotra list,gotra in hindu,telugu gothram list,same gotra marriage possible,jaiswal caste gotra,paidipala gothram,list of gotras,bhardwaj gotra list,gotra in english,rajput kuldevi list,bloggers in india,jat gotra list in hindi,angirasa gotra,sagotra marriage,vishwakarma gotra list,gotra names,vasishta gotra surnames,saini caste gotra list,pal caste gotra,chauhan rajput gotra list,nai gotra,rajput lines,soni caste gotra,surnames and gotras of brahmins,kshatriya gotra list,sunar caste gotras,panchal caste gotra,vatsa gotra brahmin,vasishta gotra,brahmin gotra in up,upmanyu gotra,gotra finder,kachwaha rajput history,gothram list in telugu,dhiman gotra,mali caste gotra,find gotra online,punjabi brahmin gotra list,kumhar samaj gotra,atri gotra surnames,gothram in telugu,kashyap gotra scheduled caste,kashyap gotra surnames list,baniya gotra list,agaci,hindu gotra list,nai samaj gotra,rajput royal,chauhan gotra list,gotra system,parmar kuldevi history,bhandari caste gotra,gotra of brahmins,rajputo ki vanshavali,pal samaj gotra'
]);


?>

<p style="text-align: center;"><strong>Every family has its own family god known as (Kuldevi and Kuldevta).To include and preserve your history for next generation.Please send an email to <a href="mailto:info@kulbeli.com">info@kulbeli.com</a>&nbsp;we will publish it in our blog</strong></p>

<div class="block">

	<div class="row">
		<div class="col-xs-12 col-md-2">
            <h1>Search In Blog</h1>
			<?php echo $this->render('_search', ['model' => $searchModel]); ?>
		</div>

		<div class="col-xs-12 col-md-10">
			<h1>Blog</h1>
			<?php if (!Yii::$app->user->isGuest and Yii::$app->user->identity->email == "abhi.vns6@gmail.com") { ?>
				<div class="form-group">
					<?= Html::a('Add new', ['post/addpost'], ['class' => 'btn btn-theme']) ?>
				</div>

				<?=
			GridView::widget([
				'showHeader' => false,
				'dataProvider' => $dataProvider,
				'columns' => [
					[
						'format' => 'html',
						'value' => function($model) {
							return "<b>" . $model->title . '</b><br><span class="text-muted">' . $model->description . '</span>';
						}
					],
					['attribute' => 'date', 'format' => ['date', 'php:d M Y']],
					[
						'format' => 'html',
						'value' => function($model) {
							return "<a class='btn btn-theme btn-xs' href='" . \yii\helpers\Url::toRoute('post/post/' . $model->id) . "'>View post</a>";
						}
					],
					[
						'format' => 'html',
						'value' => function($model) {
							return
								"<a class='text-primary' href='" . \yii\helpers\Url::toRoute('post/updatepost/' . $model->id) . "'>Update </a>" .
								"<a class='text-danger' href='" . \yii\helpers\Url::toRoute('post/deletepost/' . $model->id) . "'>Delete </a>";
						}
					],
				],
			]);
			?>
			<?php } else { ?>
			<?=
			GridView::widget([
				'showHeader' => false,
				'dataProvider' => $dataProvider,
				'columns' => [
					[
						'format' => 'html',
						'value' => function($model) {
							return "<b>" . $model->title . '</b><br><span class="text-muted">' . $model->description . '</span>';
						}
					],
					['attribute' => 'date', 'format' => ['date', 'php:d M Y']],
					[
						'format' => 'html',
						'value' => function($model) {
							return "<a class='btn btn-theme btn-xs' href='" . \yii\helpers\Url::toRoute('post/post/' . $model->id) . "'>View post</a>";
						}
					],
				],
			]);
			?>
			<?php } ?>
		</div>

		<?php /*
		<div class="col-xs-12 col-md-10">
			<table class="table table-responsive table-bordered">
				<?php

				foreach ($models as $model) {
					echo "<td><b>" . $model->title . '</b><br><span class="text-muted">' . $model->description . '</span></td>';
					echo "<td style='width: 90px;' class='text-right'>" . date('d M Y', strtotime($model->date)) . '</td>';
					echo "<td style='width: 80px;' class='text-center'>";
					echo "<a class='btn btn-theme btn-xs' href='" . \yii\helpers\Url::toRoute('post/post/' . $model->id) . "'>View post</a><br>";
					if (!Yii::$app->user->isGuest and Yii::$app->user->identity->email == "abhi.vns6@gmail.com") {
						echo "<a class='text-primary' href='" . \yii\helpers\Url::toRoute('post/updatepost/' . $model->id) . "'>Update </a>";
						echo "<a class='text-danger' href='" . \yii\helpers\Url::toRoute('post/deletepost/' . $model->id) . "'>Delete </a>";
					}
					echo "</td></tr>";
				}
				?>
			</table>

			<div class="text-center">
				<?php
				// display pagination
				echo LinkPager::widget([
					'pagination' => $pages,
				]);
				?>

			</div>
		</div>
		*/ ?>
	</div>
</div>
<script async src="//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
<!-- 2018-3 -->
<ins class="adsbygoogle"
     style="display:block"
     data-ad-client="ca-pub-2673232627079909"
     data-ad-slot="3433423721"
     data-ad-format="auto"></ins>
<script>
(adsbygoogle = window.adsbygoogle || []).push({});
</script>
