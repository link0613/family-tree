<?php
namespace app\helpers;

use app\components\MailHelper;
use app\models\Death;
use app\models\Item;

class HCron
{
	static function emailOnCelebration($test = false) {
		self::emailOnBirth($test);
		self::emailOnDeath($test);
	}

	static private function emailOnBirth($test = false) {
		$subject = self::renderSubject('birth');
		echo "\n\n================= $subject =================\n";

		/** @var Item[] $aItemBirthday */
		$aItemBirthday = Item::find()
			->select('id, b_date, user_id, first_name, middle_name, last_name, maiden_name, email')
			->where("b_date is not null and DATE_FORMAT(b_date,'%m-%d') = DATE_FORMAT(NOW(),'%m-%d')")
			->with(['itemsSameUser' => function (\yii\db\ActiveQuery $query) {
				$query->select('id, user_id, email')
					->andWhere("email is not null and email <> ''")
					->indexBy('email');
			}])
			->all();

		if ( empty($aItemBirthday) ) {
			echo __METHOD__." Nothing was found\n";
			return;
		}

		foreach ($aItemBirthday as $k=>$item) {
			$body = self::renderBody('birth', $item->getFullName(), $item->b_date);

			$aEmail = [];
			foreach ($item->itemsSameUser as $v) {
				if ($item->id != $v->id)
					$aEmail[] = $v->email;
			}

			if ( $test ) {
				echo "------- Mail #{$k} -------\n";
				print_r([
					'item ID' => $item->id,
					'$body' => strip_tags($body),
				    'to' => (empty($aEmail)) ? 'Found no members with email' : join(', ', $aEmail),
				]);
				continue;
			}

			if (empty($aEmail))
				continue;

			/** @var MailHelper $mh */
			$mh = \Yii::$app->mailHelper;
			$mh->send($aEmail, $subject, $body);
		}
	}

	static private function emailOnDeath($test = false) {
		$subject = self::renderSubject('death');
		echo "\n\n================= $subject =================\n";

		/** @var Death[] $aDeath */
		$aDeath = Death::find()
			->where("date is not null and DATE_FORMAT(date,'%m-%d') = DATE_FORMAT(NOW(),'%m-%d')")
			->with(['item' => function (\yii\db\ActiveQuery $query) {
				$query->select('id, b_date, user_id, first_name, middle_name, last_name, maiden_name, email')
					->with(['itemsSameUser' => function (\yii\db\ActiveQuery $query) {
						$query->select('id, user_id, email')
							->andWhere("email is not null and email <> ''")
							->indexBy('email');
					}]);
			}])
			->all();

		if ( empty($aDeath) ) {
			echo __METHOD__." Nothing was found\n";
			return;
		}

		foreach ($aDeath as $k=>$death) {
			$body = self::renderBody('death', $death->item->getFullName(), $death->date);

			$aEmail = [];
			foreach ($death->item->itemsSameUser as $v) {
				if ($death->item->id != $v->id)
					$aEmail[] = $v->email;
			}

			if ( $test ) {
				echo "------- Mail #{$k} -------\n";
				print_r([
					'death ID' => $death->id,
					'$body' => strip_tags($body),
					'to' => (empty($aEmail)) ? 'Found no members with email' : join(', ', $aEmail),
				]);
				continue;
			}

			if (empty($aEmail))
				continue;

			/** @var MailHelper $mh */
			$mh = \Yii::$app->mailHelper;
			$mh->send($aEmail, $subject, $body);
		}

	}






	/*********************** Admin can manage texts below ***********************/
	static private function renderSubject($type) {
		switch($type) {
			case 'birth':
				return 'Birthday reminder';

			case 'death':
				return 'Death day reminder';
		}
	}

	static private function renderBody($type, $fullName, $date) {
		switch($type) {
			case 'birth':
				return <<<HTML
<p>Today is birthday of your family member:</p>
<p>{$fullName}</p>
<p>{$date}</p>
HTML;

			case 'death':
				return <<<HTML
<p>Today is death day of your family member:</p>
<p>{$fullName}</p>
<p>{$date}</p>
HTML;
		}
	}
}