<?php

namespace app\modules\admin;
use app\models\User;
use yii\filters\AccessControl;

/**
 * admin module definition class
 */
class AdminModule extends \yii\base\Module
{
    /**
     * @inheritdoc
     */
    public $controllerNamespace = 'app\modules\admin\controllers';

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();

        // custom initialization code goes here
    }

    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['@'],
                        'matchCallback' => function($rule, $action) {
                            /** @var User $user */
                            $user = \Yii::$app->user->identity;
                            return in_array($user->email, ['infokulbeli@gmail.com', 'abhi.vns6@gmail.com']);
                        },
                    ],
                ],
            ],
        ];
    }
}
