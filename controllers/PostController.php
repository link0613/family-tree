<?php

namespace app\controllers;

use app\models\Post;
use Yii;
use yii\data\Pagination;
use yii\web\NotFoundHttpException;
use app\models\PostSearch;
use yii\data\ActiveDataProvider;

/**
 * PostController implements the CRUD actions for Post model.
 */
class PostController extends CController
{
    /**
     * Lists all Post models.
     * @return mixed
     */
    public function actionBlog()
    {
		$searchModel = new PostSearch();
        if (Yii::$app->request->queryParams) {
            $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        } else {
			$dataProvider = new ActiveDataProvider([
				'query' => Post::find()->orderBy('date DESC'),
			]);
		}

//		$query = Post::find()->orderBy('date DESC');
//        $countQuery = clone $query;
//        $pages = new Pagination(['totalCount' => $countQuery->count(), 'pageSize' => 10]);
//		$models = $query->offset($pages->offset)
//			->limit($pages->limit)
//			->all();

        return $this->render('index', [
//            'models' => $models,
//            'pages' => $pages,
			'searchModel' => $searchModel,
			'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Post model.
     * @param integer $id
     * @return mixed
     */
    public function actionPost($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Post model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     *
     * @return mixed
     * @throws NotFoundHttpException if the requested page does not exist.
     */
    public function actionAddpost()
    {
        $model = new Post();
        $model->date = date('Y-m-d');
        if ($model->load(Yii::$app->request->post())) {
            if ($model->save()) {
                return $this->redirect(['post', 'id' => $model->id]);
            }
        }

        return $this->render('create', [
            'model' => $model
        ]);
    }

    /**
     * Updates an existing Post model.
     * If update is successful, the browser will be redirected to the 'view' page.
     *
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the requested page does not exist.
     */
    public function actionUpdatepost($id)
    {
        if (Yii::$app->user->identity->email != "abhi.vns6@gmail.com") {
            throw new NotFoundHttpException('The requested page does not exist.');
        }

        $model = $this->findModel($id);
        if ($model->load(Yii::$app->request->post())) {
            $model->date = date('Y-m-d');
            if ($model->save()) {
                return $this->redirect(['post', 'id' => $model->id]);
            }
        }

        return $this->render('update', [
            'model' => $model
        ]);
    }

    /**
     * Deletes an existing Post model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     *
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException
     */
    public function actionDeletepost($id)
    {
        if (Yii::$app->user->identity->email != "abhi.vns6@gmail.com") {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
        $this->findModel($id)->delete();

        return $this->redirect(['blog']);
    }

    /**
     * Finds the Post model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     *
     * @param integer $id
     * @return Post the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Post::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
