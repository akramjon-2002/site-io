<?php

namespace frontend\controllers;

use Yii;
use common\models\Post;
use frontend\models\PostSearch;
use common\models\Tag;
use common\models\Category;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\ForbiddenHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\helpers\ArrayHelper;

/**
 * PostController implements the CRUD actions for Post model.
 */
class PostController extends Controller
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
                        'actions' => ['index', 'view'],
                        'allow' => true,
                        'roles' => ['?', '@'], // Allow all users (guests and authenticated)
                    ],
                    [
                        'actions' => ['create'],
                        'allow' => true,
                        'matchCallback' => function ($rule, $action) {
                            return Yii::$app->user->can('createPost');
                        },
                        'roles' => ['@'], // Must be authenticated
                    ],
                    [
                        'actions' => ['update'],
                        'allow' => true,
                        'matchCallback' => function ($rule, $action) {
                            $postId = Yii::$app->request->get('id');
                            $post = $this->findModel($postId); // findModel will throw NotFound if not found
                            return Yii::$app->user->can('updatePost', ['post' => $post]) || Yii::$app->user->can('updateOwnPost', ['post' => $post]);
                        },
                        'roles' => ['@'],
                    ],
                    [
                        'actions' => ['delete'],
                        'allow' => true,
                        'matchCallback' => function ($rule, $action) {
                            $postId = Yii::$app->request->get('id');
                            $post = $this->findModel($postId);
                            return Yii::$app->user->can('deletePost', ['post' => $post]) || Yii::$app->user->can('deleteOwnPost', ['post' => $post]);
                        },
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Lists all Post models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new PostSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Post model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        $model = $this->findModel($id);
        return $this->render('view', [
            'model' => $model,
        ]);
    }

    /**
     * Creates a new Post model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        if (!Yii::$app->user->can('createPost')) {
            throw new ForbiddenHttpException('You are not allowed to perform this action.');
        }

        $model = new Post();
        $model->user_id = Yii::$app->user->id; // Set current user as author

        $tagValues = ''; // For the form field

        if ($model->load(Yii::$app->request->post())) {
            $tagValues = Yii::$app->request->post('tagValues', '');
            if ($model->save()) {
                $this->updateTags($model, $tagValues);
                Yii::$app->session->setFlash('success', 'Post created successfully.');
                return $this->redirect(['view', 'id' => $model->id]);
            } else {
                Yii::$app->session->setFlash('error', 'Error creating post. Please check the form for errors.');
            }
        } else {
             // For GET request, ensure default values or relations are handled if necessary
        }


        return $this->render('create', [
            'model' => $model,
            'tagValues' => $tagValues,
        ]);
    }

    /**
     * Updates an existing Post model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     * @throws ForbiddenHttpException if the user is not allowed to update
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if (!Yii::$app->user->can('updatePost', ['post' => $model]) && !Yii::$app->user->can('updateOwnPost', ['post' => $model])) {
            throw new ForbiddenHttpException('You are not allowed to perform this action.');
        }

        $tagValues = implode(', ', ArrayHelper::map($model->tags, 'name', 'name'));

        if ($model->load(Yii::$app->request->post())) {
            $tagValues = Yii::$app->request->post('tagValues', '');
            if ($model->save()) {
                $this->updateTags($model, $tagValues);
                Yii::$app->session->setFlash('success', 'Post updated successfully.');
                return $this->redirect(['view', 'id' => $model->id]);
            } else {
                Yii::$app->session->setFlash('error', 'Error updating post. Please check the form for errors.');
            }
        }

        return $this->render('update', [
            'model' => $model,
            'tagValues' => $tagValues,
        ]);
    }

    /**
     * Deletes an existing Post model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     * @throws ForbiddenHttpException if the user is not allowed to delete
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);

        if (!Yii::$app->user->can('deletePost', ['post' => $model]) && !Yii::$app->user->can('deleteOwnPost', ['post' => $model])) {
            throw new ForbiddenHttpException('You are not allowed to perform this action.');
        }
        
        // Manually delete related tags before deleting post to avoid integrity constraint issues if not cascaded
        $model->unlinkAll('tags', true); // true to delete the junction table records

        if ($model->delete()) {
             Yii::$app->session->setFlash('success', 'Post deleted successfully.');
        } else {
             Yii::$app->session->setFlash('error', 'Error deleting post.');
        }


        return $this->redirect(['index']);
    }

    /**
     * Finds the Post model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Post the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Post::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
    
    /**
     * Helper function to update tags for a post.
     * @param Post $post The post model.
     * @param string $tagValues Comma-separated string of tag names.
     */
    protected function updateTags(Post $post, $tagValues)
    {
        // Remove existing tags
        $post->unlinkAll('tags', true);

        if (!empty($tagValues)) {
            $tagNames = array_map('trim', explode(',', $tagValues));
            foreach ($tagNames as $tagName) {
                if (empty($tagName)) continue;

                $tag = Tag::findOne(['name' => $tagName]);
                if (!$tag) {
                    $tag = new Tag(['name' => $tagName]);
                    $tag->save(); // Consider error handling for tag save
                }
                $post->link('tags', $tag);
            }
        }
    }
}
