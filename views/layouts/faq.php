<?php

/* @var $this \yii\web\View */
/* @var $content string */

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\Breadcrumbs;
use app\assets\ProfileAsset;

ProfileAsset::register($this);

$this->title = 'Kulbeli';
?>
<?php $this->beginPage() ?>
    <!DOCTYPE html>
    <html lang="<?= Yii::$app->language ?>">
    <head>
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta charset="<?= Yii::$app->charset ?>">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <?= Html::csrfMetaTags() ?>
        <title>Faq</title>
        <?php $this->head() ?>
    </head>
    <body>

    <?php $this->beginBody() ?>

    <div class="wrap">
        <div class="navbar navbar-static-top navbar-default navbar-custom" role="navigation" id="topNavbar">
            <div class="container-fluid">
                <div class="navbar-header">
                    <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
                        <span class="sr-only"><?= Yii::t('app', 'Toggle navigation') ?></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>
                    <a class="navbar-brand" href="<?= Url::toRoute('profile/index') ?>">
                        <?= $this->title ?> <?= Html::img(Url::toRoute('images/tree.svg'), ['id' => 'logo']) ?>
                    </a>

					<?php if (!Yii::$app->user->isGuest ) { ?>
                    <ul class="nav navbar-nav navbar-right">
                        <?php if (!Yii::$app->user->isGuest): ?>
                        <li class="aClass">
                            <a href="#" class="btn btn-default dropdown-toggle" style=""
                               data-toggle="dropdown" role=""
                               aria-haspopup="true" aria-expanded="false">
                                <i class="fa fa-sitemap"></i> <?= Yii::t('app', 'Familly Tree') ?>
                                <span class="caret"></span>
                            </a>
                            <ul class="options dropdown-menu">
                                <li>
                                    <a href="<?= Url::toRoute('tree/chart') ?>" class="btn">
                                        <i class="fa fa-sitemap"></i>
                                        <?= Yii::t('app', 'Familly Tree') ?>
                                    </a>
                                </li>
                                <li>
                                    <a href="<?= Url::toRoute('profile/search') ?>" class="btn">
                                        <i class="fa fa-search"></i>
                                        <?= Yii::t('app', 'Search') ?>
                                    </a>
                                </li>
                                <li>
                                    <a href="<?= Url::toRoute('profile/family') ?>" class="btn">
                                        <i class="fa fa-users"></i>
                                        <?= Yii::t('app', 'Members') ?>
                                    </a>
                                </li>
                                <li>
                                    <a class="btn" href="<?= Url::toRoute('profile/invite') ?>">
                                        <i class="fa fa-external-link" aria-hidden="true"></i>
                                        <?= Yii::t('app', 'Invite') ?>
                                    </a>
                                </li>
                            </ul>
                        </li>
                        <?php endif; ?>
                    </ul>
					<? } ?>

                </div>
                <div class="collapse navbar-collapse">
                    <ul class="nav navbar-nav navbar-right">
                        <li class="aClass" id="a">
                            <p class="navbar-btn">
                                <?php if (Yii::$app->user->isGuest): ?>
                                    <a class="btn  btn-default" data-method="post" href="<?= Url::toRoute('/') ?>">
                                        <i class="fa fa-lg fa-home"></i>
                                        <span class="text-muted"><?= Yii::t('app', 'Home'); ?></span>
                                    </a>
                                <?php endif; ?>

                                <?php if (!Yii::$app->user->isGuest && Yii::$app->user->identity->email == 'Admin'): ?>
                                    <a class="btn btn-default" href="<?= Url::toRoute('site/termsupdate') ?>">
                                        <i class="fa fa-lg fa-pencil"></i>&nbsp;
                                        <?= Yii::t('app', 'Edit terms and conditions'); ?>
                                    </a>
                                <?php endif; ?>

                                <a class="btn btn-default" data-method="post" href="<?= Url::toRoute('post/blog') ?>">
                                    <i class="fa fa-lg fa-list-alt"></i>&nbsp;<?= Yii::t('app', 'Blog'); ?>
                                </a>

                                <a class="btn btn-default" data-method="post" href="<?= Url::toRoute('forum/list') ?>">
                                    <i class="fa fa-comments-o fa-lg"></i>&nbsp;<?= Yii::t('app', 'Forum'); ?>
                                </a>

                            </p>
                        </li>

                        <?php if (!Yii::$app->user->isGuest): ?>
                            <li id="btnLogout" class="aClass">
                                <a href="#" class="btn btn-default dropdown-toggle" style=""
                                   data-toggle="dropdown" role=""
                                   aria-haspopup="true" aria-expanded="false">
                                    <i class="fa fa-user"></i>  Profile <span class="caret"></span>
                                </a>
                                <ul class="options dropdown-menu">
                                    <li>
                                        <a href="<?= Url::toRoute('profile/view/' . Yii::$app->main->getMyProfile()) ?>"
                                           class="btn text-lg">
                                            <i class="fa fa-user"></i>
                                            <?= Yii::t('app', 'My profile') ?>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="<?= Url::toRoute('profile/password') ?>" class="btn">
                                            <i class="fa fa-asterisk"></i>
                                            <?= Yii::t('app', 'Change password') ?>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="<?= Url::toRoute('profile/email') ?>" class="btn">
                                            <i class="fa fa-at"></i>
                                            <?= Yii::t('app', 'Change email') ?>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="<?= Url::toRoute('profile/remove') ?>" class="btn"
                                           data-confirm="Are you sure?">
                                            <i class="fa fa-user-times"></i>
                                            <?= Yii::t('app', 'Close My Account') ?>
                                        </a>
                                    </li>
                                </ul>
                            </li>
                        <?php endif; ?>

                        <?php if (!Yii::$app->user->isGuest): ?>
                            <li>
                                <p class="navbar-btn">
                                    <a class="btn btn-default" data-toggle="modal" data-target="#loginModal"
                                       data-method="post" style="height: 32px; margin-top: -1px;" href="<?= Url::toRoute('site/logout') ?>">
                                        <i class="fa fa-lg fa-sign-out"></i>
                                        <span class="text-muted">
                                            <?= Yii::t('app', 'Logout'); ?>
                                        </span>
                                    </a>
                                </p>
                            </li>
                        <?php endif; ?>
                    </ul>
                </div><!-- /.nav-collapse -->
            </div><!-- /.container -->
        </div>

        <div class="container-fluid" id="mainArea">
            <div class="row">
                <div class="col-xs-12">
                    <?= Breadcrumbs::widget([
                        'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
                    ]) ?>

                    <?= $content ?>

                </div>
            </div>
        </div>

        <footer class="footer" id="bottomFooter">
            <div class="col-12 col-sm-12 col-xs-12">
                <div class="block">
                    <div class="row">
                        <div class="col-12 col-sm-12 col-xs-12">
                            <?= Yii::t('app', 'If you have any questions write to:'); ?>
                            <a href="mailto:info@kulbeli.com">
                                <span class="text-info">info@kulbeli.com</span>
                            </a>
                        </div>
                    </div>
                    <hr>
                    <div class="row">
                        <div class="col-12 col-sm-12 col-md-6 col-xs-12">
                            <ul class="social ">
                                <li><h4>Follow us in social</h4></li>
                                <li><a href="https://twitter.com/KulbeliTweets"><i class="fa fa-twitter"></i></a></li>
                            </ul>
							Read our <a href="<?= Url::toRoute('site/faq') ?>">FAQ</a>
                        </div>
                        <div class="col-12 col-sm-12 col-md-6 col-xs-12 text-center">
                            <p class=" text-right"> &copy; 2016-2017 All rights reserved.</p>
                        </div>
                    </div>

                </div>
            </div>
        </footer>
        <div id="scroller"><i class="fa fa-arrow-up"></i></div>
    </div>

    <?php $this->endBody() ?>

    </body>
    </html>
<?php $this->endPage() ?>

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