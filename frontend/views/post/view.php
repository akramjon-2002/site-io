<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var common\models\Post $model */

$this->title = $model->title;
$this->params['breadcrumbs'][] = ['label' => 'Posts', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="post-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p class="text-muted">
        <small>
            Created: <?= Yii::$app->formatter->asDatetime($model->created_at) ?>
            by <?= $model->author ? Html::encode($model->author->username) : 'N/A' ?>
            in <?= $model->category ? Html::a(Html::encode($model->category->name), ['category/view', 'id' => $model->category->id]) : 'N/A' ?>.
            <?php if ($model->created_at != $model->updated_at): ?>
                Last updated: <?= Yii::$app->formatter->asDatetime($model->updated_at) ?>
            <?php endif; ?>
        </small>
    </p>

    <p>
        <?php
        // Check update permission
        $canUpdate = Yii::$app->user->can('updatePost', ['post' => $model]) || Yii::$app->user->can('updateOwnPost', ['post' => $model]);
        if ($canUpdate) {
            echo Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']);
        }
        ?>
        <?php
        // Check delete permission
        $canDelete = Yii::$app->user->can('deletePost', ['post' => $model]) || Yii::$app->user->can('deleteOwnPost', ['post' => $model]);
        if ($canDelete) {
            echo Html::a('Delete', ['delete', 'id' => $model->id], [
                'class' => 'btn btn-danger ms-2',
                'data' => [
                    'confirm' => 'Are you sure you want to delete this item?',
                    'method' => 'post',
                ],
            ]);
        }
        ?>
    </p>

    <hr>

    <div class="post-content">
        <?= Yii::$app->formatter->asNtext($model->content) // Using asNtext to preserve line breaks ?>
    </div>

    <hr>

    <?php if (count($model->tags) > 0): ?>
    <div class="tags mt-3">
        <h4>Tags:</h4>
        <p>
            <?php foreach ($model->tags as $tag): ?>
                <span class="badge bg-info me-1"><?= Html::encode($tag->name) ?></span>
            <?php endforeach; ?>
        </p>
    </div>
    <?php endif; ?>
    
    <hr>
    
    <div class="comments-section mt-4" id="comments">
        <h4>Comments (<?= count($model->comments) ?>)</h4>

        <?php if (!empty($model->comments)): ?>
            <?php foreach ($model->getComments()->orderBy(['created_at' => SORT_ASC])->all() as $comment): ?>
                <?= $this->render('@frontend/views/comment/_comment_item', [
                    'model' => $comment,
                ]) ?>
            <?php endforeach; ?>
        <?php else: ?>
            <p>No comments yet. Be the first to comment!</p>
        <?php endif; ?>

        <hr>

        <?php if (!Yii::$app->user->isGuest): ?>
            <h5 class="mt-4" id="comment-form">Leave a Comment</h5>
            <?php
            // Ensure common\models\Comment is used for the form model
            $commentModel = new \common\models\Comment(); 
            $form = \yii\bootstrap5\ActiveForm::begin([
                'action' => ['comment/create', 'postId' => $model->id],
                'method' => 'post',
            ]);
            ?>
            <?= $form->field($commentModel, 'content')->textarea(['rows' => 4, 'placeholder' => 'Write your comment here...'])->label(false) ?>
            <div class="form-group mt-2">
                <?= Html::submitButton('Submit Comment', ['class' => 'btn btn-primary']) ?>
            </div>
            <?php \yii\bootstrap5\ActiveForm::end(); ?>
        <?php else: ?>
            <p><?= Html::a('Login', ['site/login']) ?> to post a comment.</p>
        <?php endif; ?>
    </div>

</div>
