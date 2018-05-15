<?php

namespace app\modules\admin\controllers;


use app\helpers\HCron;
use app\helpers\Utils;
use Yii;
use yii\web\Controller;

class TestController extends Controller
{
	function actionCron_emailOnCelebration() {
		echo '<pre>';
		HCron::emailOnCelebration(true);
		echo '</pre>';
	}

	function actionYiic($cmd = 'migrate', $args = '') {
		set_time_limit(0);
		if ($cmd == 'migrate') {
			$args .= ' --interactive=0';
		}
		$res = Utils::yiic("$cmd $args", false);
		echo "<pre style='width: 130%;'>$res</pre>";
		Yii::$app->cache->flush();
		Yii::$app->db->schema->refresh();
	}

}