<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var common\models\Post $model */
/** @var string $tagValues */

$this->title = 'Create Post';
$this->params['breadcrumbs'][] = ['label' => 'Posts', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="post-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
        'tagValues' => $tagValues,
    ]) ?>

</div>
