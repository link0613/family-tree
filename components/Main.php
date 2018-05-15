<?php

namespace app\components;

use app\models\Item;
use app\models\Forum;
use app\models\Gotra;
use Yii;
use yii\base\Component;

class Main extends Component
{

    public function getLastAnswerDate($question_id)
    {
        $last = Forum::find()->orderBy('date DESC')
            ->joinWith('answer')
            ->where(['forum.question_id' => $question_id])
            ->one();

        return is_null($last) ? '-- -- ----' : date('d M Y', strtotime($last->answer->date));
    }

    public function getCountAnswers($question_id)
    {
        $last = Forum::find()->joinWith('answer')
            ->where(['forum.question_id' => $question_id])
            ->count('answer.date');

        return $last;
    }

    public function getProfileList($root = 1)
    {
        $list = [];
        $profiles = Item::find()->where(['user_id' => Yii::$app->user->id])->select('id,first_name,last_name, email')->orderBy('b_date DESC')->asArray();
        if ($root == 0){
            $profiles->andWhere(['root' => 0]);
        }

        $profiles = $profiles->all();

        foreach ($profiles as $item) {
            if ($root == 0){
                $list[(int)$item['id']] = ['name' => $this->checkVal($item['first_name'] . ' ' . $item['last_name']), 'email' => $this->checkVal($item['email'])];
            } else{
                $list[(int)$item['id']] = $this->checkVal($item['first_name'] . ' ' . $item['last_name']);
            }
        }

        return $list;
    }

    public function checkVal($val)
    {
        return (is_null($val) or strlen($val) < 3) ? '-----' : $val;
    }

    public function setProfileId($id)
    {
        Yii::$app->session->set('profile_id', $id);
    }

    public function getProfileId()
    {
        return Yii::$app->session->get('profile_id');
    }

    public function getMyProfile()
    {
        $id = Item::find()
            ->select('id')
            ->where([
                'user_id' => Yii::$app->user->id
            ])
            ->asArray()
            ->one();

        return (int)$id['id'];
    }

    public function getGotraId($gotraName)
    {
        $gotra = Gotra::find()->where(['name' => $gotraName])->one();

        if (is_null($gotra)) {
            $gotra = new Gotra(['name' => $gotraName]);
            $gotra->save();
        }

        return $gotra->getId();
    }

    public function searchGotraId($gotra_name)
    {
        $gotra = Gotra::find()->andFilterWhere(['like', 'name', $gotra_name])->all();

        if (is_null($gotra)) {
            return -1;
        } else {
            return $gotra;
        }
    }
}