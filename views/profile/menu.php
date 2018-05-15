<?php
use yii\helpers\Url;

?>
<div class="panel panel-default">
    <div class="panel-heading">Family Tree</div>
    <div class="panel-body">
        <ul class="options">
            <li>
                <a href="<?= Url::toRoute('tree/chart') ?>" class="btn  btn-theme"><i class="fa fa-sitemap"></i> Family
                    Tree</a>
            </li>
            <li><a href="<?= Url::toRoute('profile/search') ?>" class="btn  btn-theme"><i class="fa fa-search"></i>
                    Search</a>
            </li>
            <li><a href="<?= Url::toRoute('profile/family') ?>" class="btn  btn-theme"><i class="fa fa-users"></i>
                    Members</a>
            </li>
            <li>
                <a class="btn  btn-theme"
                   href="<?= Url::toRoute('profile/invite') ?>"><i class="fa fa-external-link" aria-hidden="true"></i>
                    Invite</a>
            </li>
        </ul>
    </div>
</div>

<div class="panel panel-default">
    <div class="panel-heading">Profile</div>
    <div class="panel-body">
        <ul class="options">
            <li>
                <a href="<?= Url::toRoute('profile/view/' . Yii::$app->main->getMyProfile()) ?>"
                   class="btn text-lg  btn-theme"><i class="fa fa-user"></i> My profile
                </a>
            </li>
            <li><a href="<?= Url::toRoute('profile/password') ?>" class="btn  btn-theme"><i class="fa fa-asterisk"></i>
                    Change password</a>
            </li>
            <li><a href="<?= Url::toRoute('profile/email') ?>" class="btn  btn-theme"><i class="fa fa-at"></i> Change
                    email</a>
            </li>
            <li><a href="<?= Url::toRoute('profile/remove') ?>" class="btn  btn-default"
                   data-confirm="Are you sure?"><i class="fa fa-user-times"></i> Close My Account</a></li>
        </ul>
    </div>
</div>



