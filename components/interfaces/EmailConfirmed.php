<?php
namespace app\components\interfaces;

interface EmailConfirmed
{
	static function emailConfirmed($email);
}