<?php

use yii\helpers\Html;
use yii\helpers\StringHelper;

/** @var common\models\Post $model */
?>

<div class="post-item mb-4 p-3 border rounded">
    <h2><?= Html::a(Html::encode($model->title), ['view', 'id' => $model->id]) ?></h2>

    <div class="text-muted small mb-2">
        <p>
            <strong>Category:</strong> <?= $model->category ? Html::a(Html::encode($model->category->name), ['category/view', 'id' => $model->category->id]) : 'N/A' ?>
        </p>
        <p>
            <strong>Author:</strong> <?= $model->author ? Html::encode($model->author->username) : 'N/A' ?>
        </p>
        <p>
            <strong>Published:</strong> <?= Yii::$app->formatter->asDatetime($model->created_at) ?>
        </p>
    </div>

    <div class="post-content-snippet">
        <?= StringHelper::truncateWords(Html::encode($model->content), 50, '...', true) ?>
    </div>
    
    <div class="mt-2">
        <?= Html::a('Read more &raquo;', ['view', 'id' => $model->id], ['class' => 'btn btn-sm btn-outline-primary']) ?>
    </div>
    
    <?php if (count($model->tags) > 0): ?>
    <div class="tags mt-2">
        <strong>Tags:</strong>
        <?php foreach ($model->tags as $tag): ?>
            <span class="badge bg-secondary"><?= Html::encode($tag->name) ?></span>
        <?php endforeach; ?>
    </div>
    <?php endif; ?>
</div>
