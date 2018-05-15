<?php
use app\components\widgets\WResearcherModal;
use yii\bootstrap\ActiveForm;
use yii\helpers\Html;
use \yii\bootstrap\Modal;
use yii\helpers\Url;
use app\components\widgets\WTracingRootsModal;

$wTRM        = new WTracingRootsModal;
$wRM         = new WResearcherModal;
$this->title = "Kulbeli: India's first Free Family Tree website, Create your family tree";
$this->registerMetaTag([
     'name' => 'description',
     'content' => 'Create your family tree and explore your family history, Create a handy and easily understandable family information with our family tree website.'
]);
$this->registerMetaTag([
     'name' => 'keywords',
     'content' => 'India Family Tree Website, Genealogy, Gotra, Free family tree search, Family tree, Family history, Ancestry, India, family tree builder, family tree chart, free family tree, family tree maker free, family tree maker free,how to make family tree,create family tree,online family tree,family tree maker download,family tree software,family tree app,family chart maker,family tree maker free,how to make family tree,create family tree,online family tree,family tree maker download,family tree software,family tree app,family chart maker,family tree chart maker,family tree websites,family tree maker online,family tree maker app,make a family tree,genealogy software,make family tree,my family tree,ancestry,family tree,genealogy,family tree maker,family history,ancestors,family tree template,free family tree,my family tree,family tree chart,how to make a family tree,find my family,create family tree,my ancestry,find your ancestors,make a family tree,family tree online,genealogist,online family tree,family tree websites,family heritage,find family,family background,family tree design,family tree diagram,how to make family tree,genealogical tree,family tree for kids,the family tree,family tree maker templates,family tree maker online,family lineage,a family tree,family chart,make family tree,family tree project,tree family,family tree examples,family tree format,family tree chart maker,family origin,family name,draw a family tree,draw family tree,family details,india census records, India history, Hindu, hinduism'
]);
?>
<script async src="//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
<script>
  (adsbygoogle = window.adsbygoogle || []).push({
    google_ad_client: "ca-pub-2673232627079909",
    enable_page_level_ads: true
  });
</script>

    <nav class="navbar navbar-default navbar-fixed-top">
        <div class="container">
            <div class="navbar-header">
                <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar"
                        aria-expanded="false" aria-controls="navbar">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
            </div>

            <div id="navbar" class="navbar-collapse collapse">
                <ul class="nav navbar-nav">
                    <li>
                        <?= Html::a(Yii::t('app', 'Home'), '#home', ['class' => "page-scroll"]); ?>
                    </li>
                    <li>
                        <?= Html::a(Yii::t('app', 'About'), '#about', ['class' => "page-scroll"]); ?>
                    </li>
                    <li>
                        <?php $wTRM->link() ?>
                    </li>
                    <li>
                        <?php $wRM->link() ?>
                    </li>
                    <li>
                        <?= Html::a(Yii::t('app', 'FAQ'), Url::toRoute('faq') ); ?>
                    </li>
                    <li>
                        <?= Html::a(Yii::t('app', 'Contact'), '#contact', ['class' => "page-scroll"]); ?>
                    </li>
                </ul>
                <ul class="nav navbar-nav navbar-right">
                    <li>
                        <p class="navbar-btn">
                            <?= Html::a(
                                '<i class="fa fa-lg fa-list-alt"></i> ' . Yii::t('app', 'Blog'),
                                Url::toRoute('post/blog'),
                                [
                                    'class' => 'btn btn-default',
                                    'data-method' => 'post'
                                ]
                            ); ?>
                            <?= Html::a(
                                '<i class="fa fa-comments-o fa-lg"></i> ' . Yii::t('app', 'Forum'),
                                Url::toRoute('forum/list'),
                                [
                                    'class' => 'btn btn-default',
                                    'data-method' => 'post'
                                ]
                            ); ?>
                            <a class="btn btn-default" data-toggle="modal" data-target="#loginModal">
                                <i class="fa fa-user"></i>&nbsp; Login
                            </a>
                        </p>
                    </li>


                </ul>
            </div><!--/.nav-collapse -->
        </div>
    </nav>
<script>
  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
  })(window,document,'script','https://www.google-analytics.com/analytics.js','ga');

  ga('create', 'UA-85896079-1', 'auto');
  ga('send', 'pageview');

</script>

<?php
Modal::begin([
    'header' => '<h3><i class="fa fa-lg fa-user"></i>&nbsp; Login form </h3>',
    'id' => 'loginModal',
    'size' => Modal::SIZE_SMALL,
    'footer' => '
            <span class="text-danger" style="font-size: 20.5px;">Don\'t have account yet?</span>
            <br>Use Registration form
            <button id="gotIt" class="btn btn-sm btn-info" data-dismiss="modal" onclick="shakeRegForm()">Got it!</button>
       '

]);
?>
    <div class="site-login">

        <?php $form = ActiveForm::begin([
            'id' => 'login-form',
            'enableAjaxValidation' => true,
            'enableClientValidation' => false,
        ]); ?>

        <?= $form->field($login, 'username')->textInput(); ?>
        <?= $form->field($login, 'password')->passwordInput(); ?>
        <?= $form->field($login, 'rememberMe')->checkbox(); ?>

        <div class="form-group">
            <?= Html::submitButton('Login', [
                'class' => 'btn btn-primary ',
                'id' => 'ajaxSubmit',
                'name' => 'login-button',
                'style' => ['width' => '100%']
            ]); ?>
        </div>

        <?php ActiveForm::end(); ?>

        <p class="text-right" style="margin: 0px;">
            <a href="<?php echo \yii\helpers\Url::toRoute('site/forgot') ?>" class=" text-danger"><b>Forgot
                    password?</b></a>
        </p>
    </div>

<?php
Modal::end();
?>

    <div id="header">

        <div class="container">
            <div class="row">
                <div class="col-xs-12 col-md-6 col-lg-6">
                    <h1 class="shadow" style="font-family: 'Courgette', cursive;">Kulbeli</h1>
                    <h2 class="subtitle shadow">Create family tree, Discover yourself, Do something memorable and create your own history.</h2>
                </div>
                <div class="col-xs-12 col-md-6 col-lg-4 col-lg-offset-2">
                    <div class="regForm">

                        <h2>Registration form</h2>
                        <?php $form = ActiveForm::begin([
                            'id' => 'signup-form',
                            'enableClientValidation' => true,
                        ]); ?>
                        <?= $form->field($profile, 'first_name', [
                            'template' => "{input}\n{hint}\n{error}"
                        ])->textInput(['placeholder' => 'First Name']) ?>
                        <?= $form->field($profile, 'last_name', [
                            'template' => "{input}\n{hint}\n{error}"
                        ])->textInput(['placeholder' => 'Last Name']) ?>
                        <?= $form->field($profile, 'gotra', [
                            'template' => '<div class="input-group">{input} <span class="input-group-addon" id="formAddon" data-toggle="tooltip"
                                  title="Hint: Clan or lineage, e.g Gautam, Kashyapa, Vasistha , Bharadwaja ect. If you don\'t know select NoGotra"><i
                                    class="fa fa-question-circle-o fa-lg "></i></span></div>{hint}',
                        ])->textInput([
                            'placeholder' => 'Gotra',
                            'style' => ['z-index' => '1']
                        ]) ?>
                        <?= $form->field($signup, 'email', [
                            'template' => "{input}\n{hint}\n{error}"
                        ])->textInput(['placeholder' => 'Email']) ?>
                        <?= $form->field($signup, 'password', [
                            'template' => "{input}\n{hint}\n{error}"
                        ])->passwordInput(['placeholder' => 'Password']) ?>
                        <div class="form-group">
                            <?php
                            echo \yii\jui\DatePicker::widget(
                                [
                                    'model' => $profile,
                                    'clientOptions' =>
                                        [
                                            'changeYear' => true,
                                            'changeMonth' => true,
                                            'showAnim' => "slideDown",
                                            'defaultDate' => '-20y',
                                            'yearRange' => "1900:-6y",
                                            'showButtonPanel' => false

                                        ],
                                    'attribute' => 'b_date',
                                    'options' => [
                                        'placeholder' => 'Select birth date...',
                                        'class' => 'form-control',
                                    ],
                                    'dateFormat' => 'yyyy-MM-dd',

                                ]);

                            ?>
                        </div>
                        <div class="form-group">
                            <div class="row">
                                <div class="col-xs-12 col-md-6">
                                    <input type="radio" name="DynamicModel[gender]" value="1" checked class="iCheck">
                                    <label class="radio-inline">Male</label>
                                </div>
                                <div class="col-xs-12 col-md-6">
                                    <input type="radio" name="DynamicModel[gender]" value="0" class="iCheck">
                                    <label class="radio-inline">Female</label>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="row">
                                <div class="col-xs-12">
                                    <input type="submit" class="btn pull-right col-xs-12  btn-success" value="Submit">

                                </div>
                            </div>
                        </div>
                        <div class="form-group text-center">
                            <i class="fa fa-lg fa-handshake-o" aria-hidden="true"></i>
                            <a target="_blank" class="text-default"
                               href="<?php echo \yii\helpers\Url::toRoute('site/terms') ?>">

                                Terms of Use and Privacy</a>
                        </div>

                        <?php ActiveForm::end(); ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

	<section id="about">
        <div class="container">
            <div class="row">
                <div class="col-xs-12">
                    <h1 class="text-center"> About Kulbeli</h1>
                    <hr>
                </div>
                <div class="col-xs-12 ">
                    <div class="text-center">
                      <p> Kulbeli is a sanskrit word which means family tree. Kulbeli is a hierarchy which shows all the family members together and how they are associated with one another. The younger generation of today has no idea about the relatives and distant members, therefore a family tree can be a superb way to help them identify the people. This not only enhances their knowledge but they find themselves close to the family as well. Creating the tree is very simple as it begins from you followed by other members in the right order. The members and then followed by their siblings and so on.</p>
                    </div>
                </div>
                <br><br>
                <div class="col-xs-12">
                    <br><br>
                    <h1 class="text-center"> Create your online family tree</h1>
                    <hr>
                </div>
                <div class="col-xs-12">
                   <div class="text-center" >
                   <p>Family means a lot for us, create your own family tree using our user friendly software. It is fun, Add names, dates, photos and stories and share with your family. Create a handy and easily understandable  family information and preserve the unique and crucial family information for your next generation. Create a handy and easily understandable family information.</p>
                </div>
            </div>
        </div>


    </section>

    <section id="contact">
        <div class="container">
            <div class="row">
                <div class="col-xs-12 col-md-6">
                    <!-- Form Name -->
                    <h2 class="text-center">Contact Form</h2>
                    <?php $form1 = ActiveForm::begin([
                        'id' => 'contact-form',
                        'enableClientValidation' => true,
                    ]); ?>
                    <?= $form1->field($contact, 'name', [
                        'template' => "
                          <div class='inputGroupContainer'>
                            <div class='input-group'>
                                <span class='input-group-addon'><i class='glyphicon glyphicon-user'></i></span>
                        {input}
                         </div>
                        </div>
                        \n{hint}\n{error}"
                    ])->textInput(['placeholder' => 'Your Name']) ?>
                    <?= $form1->field($contact, 'email', [
                        'template' => "
                          <div class='inputGroupContainer'>
                            <div class='input-group'>
                                <span class='input-group-addon'><i class='glyphicon glyphicon-envelope'></i></span>
                        {input}
                         </div>
                        </div>
                        \n{hint}\n{error}"
                    ])->textInput(['placeholder' => 'Email']) ?>
                    <?= $form1->field($contact, 'message', [
                        'template' => "
                          <div class='inputGroupContainer'>
                            <div class='input-group'>
                                <span class='input-group-addon'><i class='glyphicon glyphicon-pencil'></i></span>
                        {input}
                         </div>
                        </div>
                        \n{hint}\n{error}"
                    ])->textarea(['placeholder' => 'Message', 'rows' => 5]) ?>

                    <?= $form1->field($contact, 'verifyCode')->widget(\yii\captcha\Captcha::className(), [
                        'options' => [
                            'placeholder' => 'Enter verification code here',
                            'class' => ' form-control'
                        ],
                        'template' => '<div class="row"><div class="col-lg-3 col-md-4 col-xs-6">{image}</div>
                                        <div class="col-lg-9 col-md-8 col-xs-6">{input}</div></div>',
                    ])->label(false) ?>

                    <!-- Button -->
                    <div class="form-group">
                        <button type="submit" class="btn  pull-right btn-warning">Send <span
                                    class="glyphicon glyphicon-send"></span></button>
                    </div>

                    <?php ActiveForm::end(); ?>
                </div>



<div class="col-xs-12 col-md-6 text-right">
                    <h4><strong>Follow us in social</strong></h4>
                    <ul class="social">
                        <li><a href="https://twitter.com/KulbeliTweets"><i class="fa fa-twitter"></i></a></li>
                        <li><a href="https://www.facebook.com/kulbeli"><li><i class="fa fa-2x fa-facebook"></i></li>
                    </ul>
                    <h4><strong><?= Yii::t('app', 'Contact Us'); ?></strong></h4>
                    <p><!-- START MESSAGE  --></p>
<p><strong>Address</strong>- D162, Freedom Fighter Enclave,</p>
<p>&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;Gate Number 4, New Delhi 110068</p>
<p><strong>Email</strong>- info@kulbeli.com</p>
<p>&nbsp;</p>
<p>&nbsp;</p>
</div>


    </section>

<footer>
    Kulbeli 2016-2017 &copy; All rights reserved
</footer>
    <div class="modal fade" id="ieWarning">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header" style="padding: 8px;">
                    <p>
                        Browser is not supporting
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    </p>
                </div>
                <div class="modal-body">
                    <?= Yii::t('app', 'Application is not supported by your browser. We are notified and working
                    on enabling it, meanwhile, please install and use
                    <a href="https://www.google.com/chrome/browser/desktop/index.html">Google Chrome.</a>'); ?>
                </div>
            </div>
        </div>
<script type="text/javascript" src="https://cdn.ywxi.net/js/1.js" async></script>

    </div>

<?php $wTRM->modal() ?>
<?php $wRM->modal() ?>
<?php
$this->registerJs("
    $(document).ready(function () {
        // Internet Explorer 6-11
        var isIE = /*@cc_on!@*/false || !!document.documentMode;
        if(0&& isIE){
            $('#ieWarning').modal('show');

            // Your application has indicated there's an error
            window.setTimeout(function(){
                // Move to a new location or you can do something else
                window.location.href = \"https://www.google.com/chrome/browser/desktop/index.html\";
            }, 10000);
        }
    });

    $(document).ajaxSend(function(){
        $('#ajaxSubmit').html('Wait...');
        $('#ajaxSubmit').prop('disabled',true);
    });
    $(document).ajaxComplete(function () {
        var error = $(\"#login-form\").find('.has-error');
        if (!error)
        {
            location.reload();
        }
        else
        {
            $('#ajaxSubmit').html('Login');
            $('#ajaxSubmit').prop('disabled',false);
        }
    });
");

