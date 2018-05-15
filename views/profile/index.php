<?php
use yii\helpers\Html;
use yii\helpers\Url;

?>
<div class="block">
    <h2 class="text-center">Greetings !</h2>
    <br>
    <div class="jumbotron">
        <p class="text-center">
            Hello <?= $profile->first_name, ' ', $profile->last_name ?>,
            <p><!-- START MESSAGE  --></p>
<table border="0" width="100%" cellspacing="0" cellpadding="0" bgcolor="#ffffff">
<tbody>
<tr>
<td align="center">
<table class="container" style="height: 229px;" border="0" width="623" cellspacing="0" cellpadding="0">
<tbody>
<tr>
<td class="mobile" align="center" width="600">
<p>We just wanted to send you a quick note to say welcome to Kulbeli and thank you&nbsp;for signing up!</p>
<p><strong>What next?</strong></p>
<p>Here are a few steps you can take to get started and see how Kulbeli works...&nbsp;</p>
<ul style="list-style-type: square;">
<li style="text-align: left;">Add family members to your family tree.</li>
<li style="text-align: left;">Add Family members photos, birthdays and death anniversary, Kulbeli will notify such events to you;</li>
<li style="text-align: left;">Send invitation to your family members for creating an extended family tree</li>
<li style="text-align: left;">Tell your friends about Kulbeli</li>
</ul>
<p><strong>Feedback</strong></p>
<p>We&rsquo;re working hard to make Kulbeli even more wonderful. Please give us your feedback by emailing us (<a href="mailto:help@kulbeli.com"><span style="text-decoration: underline;">info@kulbeli.com</span></a>)</p>
</td>
</tr>
</tbody>
</table>
</td>
</tr>
</tbody>
</table>
<p>&nbsp;</p>
        </p>
    </div>
    <p class="text-right">
        <?= Html::a('Create Your FamilyTree', Url::toRoute('tree/branch'), ['class' => 'btn btn-lg btn-theme']); ?>
    </p>
    <br>
    <br>
</div>