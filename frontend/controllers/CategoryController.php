<?php

namespace frontend\controllers;

use Yii;
use common\models\Category;
use common\models\Post;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

/**
 * CategoryController displays posts filtered by category.
 */
class CategoryController extends Controller
{
    /**
     * Displays posts belonging to a specific category.
     * @param integer $id the ID of the category.
     * @return mixed
     * @throws NotFoundHttpException if the category model cannot be found.
     */
    public function actionView($id)
    {
        $category = $this->findModel($id);

        $dataProvider = new ActiveDataProvider([
            'query' => Post::find()->where(['category_id' => $id])->orderBy(['created_at' => SORT_DESC]),
            'pagination' => ['pageSize' => 10,],
        ]);

        return $this->render('view', [
            'category' => $category,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Finds the Category model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Category the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Category::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested category does not exist.');
    }
}
