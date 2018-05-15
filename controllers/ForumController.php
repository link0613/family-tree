<?php

namespace app\controllers;

use app\models\Answer;
use app\models\Forum;
use app\models\Question;
use Yii;
use yii\data\Pagination;
use app\models\QuestionSearch;
use yii\data\ActiveDataProvider;

/**
 * ForumController implements the CRUD actions for Forum model.
 */
class ForumController extends CController
{

    public function actionList()
    {
		$searchModel = new QuestionSearch();
        if (Yii::$app->request->queryParams) {
            $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        } else {
			$dataProvider = new ActiveDataProvider([
				'query' => Question::find()->orderBy('date DESC')->with('item'),
			]);
		}

//        $query = Question::find()->orderBy('date DESC');
//        $countQuery = clone $query;
//        $pages = new Pagination(['totalCount' => $countQuery->count(), 'pageSize' => 10]);
//        $models = $query->offset($pages->offset)
//            ->limit($pages->limit)
//            ->asArray()
//            ->all();

		return $this->render('index', [
//            'models' => $models,
//            'pages' => $pages,
			'searchModel' => $searchModel,
			'dataProvider' => $dataProvider,
        ]);
    }

    public function actionQuestion($open = 1)
    {
        $model = new Question(['user_id' => Yii::$app->user->id, 'open' => $open, 'date' => date('Y-m-d')]);
        $model->user_id = Yii::$app->user->id;
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['list']);
        } else {
            return $this->render('question', [
                'model' => $model,
            ]);
        }
    }

    public function actionClose($id)
    {
        $question = Question::find()->where(['id' => $id, 'user_id' => Yii::$app->user->id])->one();
        if (!is_null($question)) {
            $question->delete();
        }
        return $this->redirect(['list']);
    }

    public function actionDelete($id)
    {
        $forum = Forum::find()->where(['question_id' => $id])->joinWith(['question', 'answer'])->all();
        if (Yii::$app->user->identity->email != "Admin" or $forum->question->user_id == Yii::$app->user->id) {
            foreach ($forum as $item) {
                if (isset($item->question)) {
                    $item->question->delete();
                }
                if (isset($item->answer)) {
                    $item->answer->delete();
                }
                $item->delete();
            }
        }
        return $this->redirect(['list']);
    }

    public function actionDiscuss($id)
    {
        $answer = new Answer(['user_id' => Yii::$app->user->id, 'date' => date('Y-m-d')]);
        if ($answer->load(Yii::$app->request->post()) && $answer->save()) {
            $forum = new Forum(['answer_id' => $answer->id, 'question_id' => $id]);
            $forum->save();
            return $this->refresh();
        }
        $answers = Forum::find()->orderBy('date DESC')
            ->joinWith('answer')
            ->where(['forum.question_id' => $id])
            ->all();

        $question = Question::find()->where(['id' => $id])->with('item')->one();
        return $this->render('view', [
            'answers' => $answers,
            'answer' => $answer,
            'question' => $question
        ]);
    }
}
