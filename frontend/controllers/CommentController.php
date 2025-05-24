<?php

namespace frontend\controllers;

use Yii;
use common\models\Comment;
use common\models\Post;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\ForbiddenHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\helpers\Url;

/**
 * CommentController implements the CRUD actions for Comment model.
 */
class CommentController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'rules' => [
                    [
                        'actions' => ['create'],
                        'allow' => true,
                        'roles' => ['@'], // Allow authenticated users
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'create' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Creates a new Comment model.
     * @param integer $postId
     * @return mixed
     * @throws NotFoundHttpException if the associated Post model cannot be found
     */
    public function actionCreate($postId)
    {
        $post = Post::findOne($postId);
        if (!$post) {
            throw new NotFoundHttpException('The requested post does not exist.');
        }

        $model = new Comment();
        $model->post_id = $postId;
        $model->user_id = Yii::$app->user->id;

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            Yii::$app->session->setFlash('success', 'Your comment has been added.');
            return $this->redirect(Url::to(['post/view', 'id' => $postId, '#' => 'comment-' . $model->id]));
        } else {
            // Handle validation errors or other save issues
            $errors = $model->hasErrors() ? implode(' ', $model->getFirstErrors()) : 'Could not save comment.';
            Yii::$app->session->setFlash('error', 'Error adding comment: ' . $errors);
            // It's generally better to re-render the form with errors if possible,
            // but for simplicity, we redirect back to the post view.
            // The user will have to re-type their comment.
            return $this->redirect(Url::to(['post/view', 'id' => $postId, '#' => 'comment-form']));
        }
    }
}
