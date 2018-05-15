<?php

namespace app\commands;


use app\helpers\HCron;
use yii\console\Controller;

class DailyController extends Controller
{
	function actionIndex() {
		HCron::emailOnCelebration();
	}

}