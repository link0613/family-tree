<?php

namespace app\components;

use Yii;
use yii\base\Component;
use yii\base\Exception;
use yii\helpers\Url;

class MailHelper extends Component
{
	function send($to, $subject, $body, $isHtml = true) {
		/** @var \yii\swiftmailer\Mailer $mailer */
		$mailer = Yii::$app->mail;
		$msg = $mailer->compose()
			->setFrom([Yii::$app->params['websiteAdmin'] => 'Kulbeli'])
			->setTo($to)
			->setSubject($subject);
		if ($isHtml) {
			$msg->setHtmlBody($body);
		} else {
			$msg->setTextBody($body);
		}
		return $msg->send();
	}

	function sendToAdmin($subject, $body, $isHtml = true) {
		return $this->send(Yii::$app->params['adminEmail'], $subject, $body, $isHtml);
	}

	/**
	 * @param $email
	 * @param \yii\base\Object|\app\components\interfaces\EmailConfirmed $o
	 * @return bool
	 * @throws Exception
	 */
	function sendConfirmation($email, \yii\base\Object $o, $addBody = '') {
		$subject = $this->renderSendConfirmationSubject($o);
		$hash = $this->hash($email, $this->getSalt($o));
		$url = Url::toRoute(['site/confirm-email', 'email' => $email, 'hash' => $hash], true);
		$body = $this->renderSendConfirmation($url, $addBody);

		return $this->send($email, $subject, $body);
	}

	/**
	 * @param $email
	 * @param $hash
	 * @return null|\app\components\interfaces\EmailConfirmed
	 */
	function confirm($email, $hash) {
		foreach ($this->getSaltsAll() as $class => $salt) {
			if ( $hash == $this->hash($email, $salt) ) {
				return new $class;
			}
		}

		return null;
	}

	private function hash($email, $salt) {
		return md5($email.$salt);
	}

	private function getSaltsAll() {
		return [
			\app\models\TrApplicant::className() => '[s[dmbtyasdnb34756gdsof943h',
			\app\models\Researcher::className() => 'khsdf7834.scv-0adfbjh234][',
			\app\models\User::className() => 'kdasfjb87dfb6123f][cda8sbjh234',
		];
	}

	/**
	 * @param \yii\base\Object|\app\components\interfaces\EmailConfirmed $o
	 * @return mixed
	 * @throws Exception
	 */
	private function getSalt(\yii\base\Object $o) {
		$cfg = $this->getSaltsAll();

		if ( !isset($cfg[$o::className()]) )
			throw new Exception('Unexpected object');

		return $cfg[$o::className()];
	}





	function renderSendConfirmationSubject(\yii\base\Object $o) {
		switch ($o->className()) {
			case \app\models\TrApplicant::className():
				return 'Kulbeli: Acknowledgment of Tracing your roots';

			case \app\models\Researcher::className():
				return 'Kulbeli: Acknowledgment of Becoming Researcher';

			default:
				return 'Welcome to Kulbeli! Email verification';
		}
	}

	function renderSendConfirmation($url, $addBody = '') {
		return <<<HTML
<p>To confirm your email follow this link: <a href='{$url}'>{$url}</a></p>
{$addBody}
HTML;
	}

	function renderApplicationNumber($uid) {
		return <<<HTML
<br>
<p>Your application number is '<b>{$uid}</b>'. We Will get back to you.</p>
<br>
<p>Thank you</p>
<p>Team Kulbeli</p>
HTML;
	}
}