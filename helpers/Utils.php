<?php

namespace app\helpers;


use Yii;

class Utils
{
	static function yiic($args, $isBackground = true) {
		$appPath = Yii::getAlias('@app');
		$php = Utils::getShellExecPrefixPHP();
		$background = ($isBackground) ? Utils::getShellExecBackgroundMode() : '';
		return shell_exec("$php $appPath/yii $args $background");
	}

	static function getShellExecPrefixPHP() {
		return (self::isLiveSite()) ? 'php' : 'C:/php/php.exe';
	}

	static function isLiveSite() {
		if ( isset($_SERVER['HTTP_HOST']) && in_array($_SERVER['HTTP_HOST'], ['kulbeli.int'])
			||
			isset($_SERVER['USERNAME']) && in_array($_SERVER['USERNAME'], ['EgorKa'])
		) {
			return false;
		}

		$res = true;
		return $res;
	}

	static function getShellExecBackgroundMode() {
		return (self::isLiveSite()) ? '> /dev/null 2>/dev/null &' : false;
	}

}