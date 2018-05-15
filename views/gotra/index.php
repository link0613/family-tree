<?php

use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\widgets\ListView;
use yii\widgets\Pjax;
use yii\widgets\LinkPager;
use yii\grid\GridView;


/** @var array $castes */
/** @var string $selectedCaste */


$this->title = 'Gotra';

$this->params['breadcrumbs'][] = $this->title;

$this->registerMetaTag([
'name'=> 'title',
'content'=>'Kulbeli: Complete Hindu Gotra list, Vanshawali'
]);
$this->registerMetaTag([
     'name' => 'description',
     'content' => 'Complete list of Hindu Gotra, Brahmin Gotra'
]);
$this->registerMetaTag([
     'name' => 'keywords',
     'content' => 'List of gotra,Gotra,Atri,Jamadagni,Vishvamitra,Brahmin,List of Brahmin gotra,List of Dhangar, clans in India,Hinduism,Agastya,Bhargava, Rajput Gotra , Vansh, Purvaj, Ancestor, Vanshavali, India Roots,India origin, Khandan, Pidhi, Surname, Caste, Brahamin Vanshavali, Rajput Vanshavali, Gotravali, Gotra List, what is gotra, Vanshavali and Gotras, Trace Your Ancestors From India, India Family Tree, Free family tree search, India Genealogy,gotra,writing websites,blog websites,nepali surnames,find caste by surname,gotra list,bhardwaj caste,same gotra marriage solution,brahmin surnames,gaur brahmin gotra list,brahmin gotra,family tree websites,same gotra marriage solution in hindi,branch tree,brahmin gotra list in hindi,list gotra kuldevi,brahmin surnames list,kayastha gotra list,teli caste gotra,telugu brahmin surnames and gotras,brahmin surnames and gotras,brahman samaj gotra,soni samaj gotra list,kashyap caste,kashyap gotra caste,valmiki caste gotra,kashyap gotra surnames,same gotra marriage,indian caste names list,jatav gotra list,saryuparin brahmin gotra list,kashyap gotra belongs to which caste,rig veda,jatav gotra,thakur caste gotra,kashyap caste history in hindi,jatav caste gotra,nepali caste and gotra,rajput gotra list,kashyap rajput gotra list,vedic books,hindu religious books,rajput gotra list in hindi,mala caste gotra,website india,vaishnav samaj gotra list,kaundinya gotra,kashyap surname belongs to which caste,atharva veda,gotra in hindi,brahmin gotra list,vishwakarma caste gotras,vedas in english,veda,bhatt brahmin gotra,brahmin caste surnames list,devanga caste surnames and gotras,brahmin gotras and surnames,marriage in same gotra,garg gotra,rajput gotra,all brahmin surnames list,vedas and upanishads,kaushal gotra,hindu vedas,bhardwaj gotra surnames,nepali caste list,kshatriya caste surnames,shiva gotra,khatri gotra,vedic philosophy,kaushik gotra,bhandari surname,books on vedas,vedas online,gowda caste gotra,adhikari caste,same gotra marriage effect,atri gotra,gotra of vishwakarma,rigveda,hindu gotra,four vedas,the vedas,scheduled caste gotra list,gotra in hindu,telugu gothram list,same gotra marriage possible,jaiswal caste gotra,suryavanshi caste,paidipala gothram,atharva veda mantras,list of gotras,bhardwaj gotra list,gotra of rajput,4 vedas,gotra in english,yadav caste gotra,khatri surnames,moudgalya gotra,jat gotra list in hindi,angirasa gotra,the rig veda,sagotra marriage,vishwakarma gotra list,gautam caste,marathi brahmin surnames list,marathi brahmin surnames,indian vedas,gotra names,vasishta gotra surnames,hindu brahmin surnames list,saini caste gotra list,brahmin caste list,vedic literature,haritasa gotra,chauhan rajput gotra list,kasyapa gotra,manipal india,nai gotra,brahmin caste surnames,rig veda book,soni caste gotra,surnames and gotras of brahmins,kshatriya gotra list,sunar caste gotras,hindu book,panchal caste gotra,vatsa gotra brahmin,vedic civilization,vasishta gotra,vedic culture,upmanyu gotra,gotra finder,bhardwaj surname,gothram list in telugu,kashyap surname,dhiman gotra,mali caste gotra,find gotra online,punjabi brahmin gotra list,kumhar samaj gotra,atri gotra surnames,list of kuldevi,gothram in telugu,kashyap gotra scheduled caste,shandilya gotra surnames,pandey caste gotra,kashyap gotra surnames list,shandilya caste,baniya gotra list,rawat rajput gotra,vedic religion,hindu gotra list,list of brahmins surnames,vedic age,brahmin vanshavali,chauhan gotra list,gotra system,family tree india,rajput gotra in hindi,bhandari caste gotra,gotra of brahmins,jat gotra in hindi,hindu scriptures,savarna gotra,vedic period,bania caste gotra,pal samaj gotra,brahmin names,gotra list of kshatriyas,ancient indian books,gothram names list in telugu,nepali surname,gotra in marathi,vaishya gotra list,sunar gotra list,gurjar gotra,vats caste,brahmin gotravali in hindi,siva gothram,websites in india,religious book of hinduism,bhardwaj gotra in hindi,gotra in brahmins,sindhi gotra list,gotra in india,shandilya gotra history in hindi,bhardwaj caste category,yadav gotra list,vadula gothram,hinduism holy book,agarwal gotra,jamadagni gotra,bengali gotra list,vasishtha gotra,four vedas in hindi,navigation india,suryavanshi rajput gotra,gargya gotra,gotra of rajput kshatriya,rg veda,list of brahmin gotras,gaur brahmin gotra list in hindi,kumhar gotra list,kaundinya gotra surnames,punjabi gotra list,holy book of hinduism,kashyap gotra in brahmin,vedas in sanskrit,alambayan gotra,four vedas in english,vedic sanskrit,early vedic period,vedic hymns,vedic scriptures,prajapati caste gotra,padmashali gothram list in telugu,maratha gotra list,punjabi brahmin gotras,the four vedas,caste by surname,brahmin last names,sanskrit vedas,thapa caste,4 ved in hindi,bhargava caste,india navigation,vedic knowledge,kaushik gotra surnames,gotra search by surname,know your gotra by surname,vashisht gotra,jain gotra list,vatsa gotra surnames,the vedic age,all rajput gotra list,saraswat brahmin gotra list,kashyap belongs to which caste,vedic texts,kashyap caste in hindi,kuldevi list,thakur gotra,punjabi brahmin surnames list,telugu gothram names,khatri caste surnames list,jain gotra,vedas book in english,gotra list in marathi,veda name,kayastha surnames list,later vedic period,paidipala gothram caste,book of hinduism,gautam gotra,vaishya gotra,bhardwaj rishi history in hindi,original vedas,bhardwaj gotra history in hindi,maharshi gautam,agarwal caste,teli samaj gotra,4 vedas in english,kashyap gotra history,dhanak caste gotra list,marwari brahmin surnames,baniya surnames,pal caste gotra,kshatriya surnames,rathore gotra list,agarwal gotra list,vedic history,rig veda summary,lohar caste gotra,garg caste,vedas upanishads,brahmin gotra in up,vedic india,gautam surname,vedic time,ghotra caste,ved book,ancient indian literature,saraswat brahmin gotra,atharva veda sanskrit,baniya surnames list,list of sunar gotra,hindu caste list surnames,all vedas,teachings of vedas,the vedic period,kashyap gotra history in hindi,gautam caste category,general category caste list in up,gautam surname caste,hindu texts,nai samaj gotra,vedic teachings,pandit caste surnames,singhal caste,vedic hinduism,jindal family tree,name of four vedas,kayastha caste gotra,indian scriptures,madhuri madhuri,arora gotra list,valmiki gotra list,agarwal gotra list in hindi,rig vedic period,ancient vedas,gotra list of rajput,name of 4 vedas,gotra matching list,the 4 vedas,khatri gotra list,list of gotra and kuldevi,srivastava gotra,bhandari surname caste,the vedic civilization,name of vedas,arora caste gotra,suryavanshi surname belongs to which caste,khati samaj gotra,four veda,kashyap caste comes in which category,kashyap rajput history in hindi,gautam surname belongs to which caste,goel caste,indian rights,kurmi gotra list,surnames of brahmins,kashyap gotra in hindi,pandit caste list,punjabi brahmin surnames,mahajan caste gotra,veda purana,vedic era,bhandari caste surname,the atharva veda,goyal caste,gotra of thakur,5 vedas,the holy book of hinduism,back to vedas,indian caste list,indian surnames,hindu caste list,caste category,indian caste system surnames,general caste list,indian caste system list,hindu caste names,indian caste list surname,caste category list,general category caste list,caste system in hinduism,indian caste category list,general caste list in up,4 castes,surname wise caste,kshatriya caste list,rajput caste list,punjabi surnames,telugu brahmin surnames,yadav caste surnames list,mehra caste,maratha caste surnames,rajput caste surnames list,indian surnames list,surname and caste list,muslim caste list,jat gotra,gujarati brahmin surnames list,chettiar caste list,gujarati brahmin surnames,hindu surnames,list of marathi surnames with caste,teli samaj surname list,indian surname caste list,all caste list,naidu caste category,maratha caste list,kshatriya caste system,general caste list in hindu,indian cast list,sub caste list,surname caste list,telugu brahmin surnames list,list of general caste in up,caste names,punjabi caste surnames,scheduled caste in marathi,chamar caste surnames list,kumhar caste surnames,teli caste surnames,madiga caste surnames,voice artist,dhobi caste surnames,bengali surnames and caste list,rajput caste category,jat caste surnames list,punjabi last names,deshastha brahmin surnames,hindu surnames with caste,bengali brahmin surnames,books on hinduism,madiga caste surnames list,muslim caste names,caste in hindi,bania caste list,punjabi khatri caste list,surname caste,hindu mantras,hindu baby names,nai caste surnames,brahmin caste system,hindu calendar,baby names hindu,rajputs surnames,hindu religion,kokanastha brahmin surnames,god hindu,open caste list,list of general caste,baby names boy hindu,indian hindu baby boy names,caste by surname search,darji caste,rajput caste names,indian baby girl names,kshatriya caste belongs to which category,hindu baby boy names,baby boy names hindu,list of khatri caste,south indian surnames,jain caste surnames list,caste name list,indian girl baby names,hindu boy baby names,indian boy names,indian baby names for girls,boy names hindu,khatri caste list,baby names girl hindu,indian hindu baby names,surname and caste,bengali brahmin surnames list,hindu boy names,shudra caste list,indian last names list,arora caste list,padmashali gothram tree,brahmin sub caste list,hindu baby boy names with meaning,hindu baby girl names,hindu maratha caste surnames,hinduism festivals,hindu girl baby names,caste category list in hindi,kshatriya maratha surnames list,caste brahmin,telugu festivals 2016,caste and subcaste list,marathi surnames and caste list,caste in hinduism,hindu baby names boy,home listings,muslim cast list,bengali caste wise surname,list of caste,hindu festival calendar,hindu festivals 2016,vaishya caste list,boy name in hindi,teli caste surname list,yadav sub caste list,gujarati caste list,gujarati caste system by surname,kshatriya surnames list,bengali caste surnames,indian festivals list,all in one,general caste category list,rajput surnames list,kanyakubj brahmin surnames,hindu culture,vaishya caste surnames,bengali kayastha surnames list,baby girl names hindu,hinduism history,maharashtrian brahmin surnames,thakur caste surnames,padmashali gothram list,surname wise caste list,hindu festivals,list of brahmins,hindu women,brahmin caste comes under which category,caste and subcaste,hindu baby names girl,surnames and their caste,viswabrahmin surnames and gotras,marathi surnames and caste,marathi caste list,list of indian festivals,beliefs of hinduism,hinduism beliefs,telugu surnames list,download hanuman chalisa,hindu boy name list,hindu rituals,hanuman chalisa hanuman chalisa,mp3 hanuman chalisa,list of telugu brahmin surnames,hindu,vaishnav caste surnames,baby names in hindu,indian gods,maratha caste surname list,all punjabi surnames,2016 telugu festivals,hinduism and science,sudra caste list,giri surname belongs to which caste,bihari brahmin surnames,more surname caste,hindu names for boys,hindu baby name list,nair caste gotra,bengali caste list,hindu gods,baby boy names in hindi,hindu holidays,jain surnames list,hindu jati list,punjabi surname list,kokanastha brahmin surnames list,sub caste of hindu,hindu religion history,the hindu,rajasthani brahmin surnames,hindu surnames list,brahmin sub caste,hinduism in world,name of baby boy hindu,hindu god shiva,khatri caste surnames,south indian surnames list,list of sub caste in general category,scheduled caste surnames,hindu gods names,telugu festivals,darji caste surnames,teachings of hinduism,indian festival calendar,marathi surname list,gotra pravara in telugu,different castes,baby boy hindu names,maratha surnames caste,velama caste gotras,hindu girl,ganesh god,list of hindu boy baby names,bengali kshatriya surnames,general cast list,ganesh,brahmin caste category,indian goddess,hindi surnames,hindi names for boys,boy name in hindu,gotra pravara,hindu male baby names,hindu teacher,hindu baby names for girls,hindu mythology,komati caste surnames,hindu goddesses,name of baby girl hindu,ganesh names,list of rajput surnames,list of caste under general,full hanuman chalisa,hindu names for baby boy,hindu worship,hindu religion in hindi,indian religions list,hindu female baby names,indian hindu girl baby names,hindu deities,name of hindu baby boy,childran name boy,marathi kayastha surnames,hindu baby name in hindi,hindu puja,karma hinduism,hindu girl baby names with meaning,baby names hindu boy,indian last names,jat gotras,krishnatreya gotra,marathi rajput surnames,baby girl hindu names,list of indian castes by rank,brahman hinduism,indian goddess names,new hindu baby boy names,hindu countries,name of hindu baby girl,indian surnames list punjabi,jain gotra kuldevi,telugu festivals in 2016,indian boys names list,indian goddesses,newborn baby boy names hindu,new born baby name boy hindu,indian hindu boy baby names,indian baby boy name list,hindu people,gotra of agarwal,indian surname list alphabetical,newborn baby boy names hindu with meaning,hindu meaning'
]);

?>
<p style="text-align: center;"><strong>We are consolidating all gotra in one place, please send an email to <a href="mailto:info@kulbeli.com">info@kulbeli.com</a>&nbsp;to include and preserve your gotra for next generation.</strong></p>

<div class="block">

    <div class="row">
        <div class="col-xs-12 col-md-2">
            <h1>Select caste</h1>
            <!-- @todo add dropdown -->
            <?= Html::dropDownList('caste', $selectedCaste, $castes, ['class' => 'form-control', 'id' => "casts-list"]) ?>

<p>&nbsp;</p>
<p>&nbsp;</p>
<p>&nbsp;</p>



<script async src="//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
<!-- 2018 -->
<ins class="adsbygoogle"
     style="display:block"
     data-ad-client="ca-pub-2673232627079909"
     data-ad-slot="3600944687"
     data-ad-format="auto"></ins>
<script>
(adsbygoogle = window.adsbygoogle || []).push({});
</script>

        </div>

        <div class="col-xs-12 col-md-10">
            <h1>Gotra</h1>
                <?=
                GridView::widget([
                    'showHeader'   => true,
                    'dataProvider' => $dataProvider,
                    'columns'      => [
                        //'id',
                        'gotra',
                        //'original_gotra',
                        'lord',
                       // 'saint',
                       // 'veda',
                        'branch',
                        //'sutra',
                    ],
                ]);
                ?>
        </div>
    </div>
<div id="disqus_thread"></div>
<script>

/**
*  RECOMMENDED CONFIGURATION VARIABLES: EDIT AND UNCOMMENT THE SECTION BELOW TO INSERT DYNAMIC VALUES FROM YOUR PLATFORM OR CMS.
*  LEARN WHY DEFINING THESE VARIABLES IS IMPORTANT: https://disqus.com/admin/universalcode/#configuration-variables*/
/*
var disqus_config = function () {
$this->params['breadcrumbs'][] = ['label' => 'Gotra', 'url' => ['Gotra']];
$this->params['breadcrumbs'][] = $this->title;  // Replace PAGE_URL with your page's canonical URL variable
 // Replace PAGE_IDENTIFIER with your page's unique identifier variable
};
*/
(function() { // DON'T EDIT BELOW THIS LINE
var d = document, s = d.createElement('script');
s.src = 'https://www-kulbeli-com.disqus.com/embed.js';
s.setAttribute('data-timestamp', +new Date());
(d.head || d.body).appendChild(s);
})();
</script>
<noscript>Please enable JavaScript to view the <a href="https://disqus.com/?ref_noscript">comments powered by Disqus.</a></noscript>
<script id="dsq-count-scr" src="//www-kulbeli-com.disqus.com/count.js" async></script>                           

</div>
</div>


