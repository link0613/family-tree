<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\filters\VerbFilter;

class CController extends Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => [
                            'blog',
                            'cron',
                            'test',
                            'captcha',
                            'pdf',
                            'print',
                            'index',
                            'error',
                            'terms',
                            'forgot',
                            'reset',
                            'post',
'faq',
                            'list',
                            'discuss',
                            'tracing-roots',
                            'confirm-email',
                            'become-researcher',
                        ],
                        'allow' => true,
                        'roles' => ['?', '@'],
                    ],
                    [
                        'actions' => [
                            'chart',
                            'index',
                            'delete',
                            'invite',
                            'first',
                            'image',
                            'show',
                            'search',
                            'password',
                            'email',
                            'view',
                            'education',
                            'educationedit',
                            'contact',
                            'contactedit',
                            'set',
                            'family',
                            'remove',
                            'question',
                            'close',
                            'logout',
                            'spouses',
                            'marriages',
                            'children',
                            'privacy',
                            'create'
                        ],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                    [
                        'actions' => [
                            'addpost',
                            'termsupdate',
                            'updatepost',
                            'deletepost'
                        ],
                        'allow' => true,
                        'roles' => ['@'],
                        'matchCallback' => function () {
                            return !Yii::$app->user->isGuest and Yii::$app->user->identity->email == "abhi.vns6@gmail.com";
                        }

                    ],

                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    public function actions()
    {

        Yii::$app->session->open();
        return [
            $this->layout = 'profile',
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
        ];
    }
}
