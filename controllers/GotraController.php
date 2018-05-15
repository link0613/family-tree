<?php

namespace app\controllers;

use app\models\Caste;
use yii\data\ActiveDataProvider;

class GotraController extends CController
{
    /**
     * Available casts.
     *
     * @var array
     */
    protected static $castes = [
        1 => 'Agarwal',
        2 => 'Oswal',
        3 => 'Bhavsar Kshtriya',
        4 => 'Prajapati or Kummara',
        5 => 'Maheshwari',
        6 => 'Yadav',
        7 => 'Jat',
        8 => 'Brahmin',
        9 => 'Rors',
        10 => 'Arya Vysyas',
        11=> 'Velama ',
        12=>'Maheshwaries',
 13=>'Khatik',
14=>'Gurjar Gaud',
    ];

    /**
     * Shows all castes or selected one.
     *
     * @return string
     */
    public function actionIndex()
    {
        $caste = \Yii::$app->request->get('id');

        if (empty($caste) || !array_key_exists($caste, static::$castes)) {
            $caste = 1;
        }

        $casteName = static::$castes[$caste];

        $query = Caste::find()->where(['caste' => $casteName]);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        return $this->render('index', [
            'selectedCaste' => $caste,
            'castes'       => static::$castes,
            'dataProvider' => $dataProvider,
            'pagination'   => [
                'pageSize' => 50,
            ],
        ]);
    }

}
