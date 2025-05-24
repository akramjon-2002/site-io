<?php

use yii\helpers\Html;

/** @var common\models\Comment $model */
?>

<div class="comment-item mb-3 p-3 border rounded" id="comment-<?= $model->id ?>">
    <div class="comment-author fw-bold">
        <?= Html::encode($model->author ? $model->author->username : 'Anonymous') ?>
    </div>
    <div class="comment-meta text-muted small mb-1">
        <?= Yii::$app->formatter->asRelativeTime($model->created_at) ?> 
        (<?= Yii::$app->formatter->asDatetime($model->created_at, 'medium') ?>)
    </div>
    <div class="comment-content">
        <?= nl2br(Html::encode($model->content)) // nl2br to respect line breaks ?>
    </div>
</div>
