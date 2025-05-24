<?php

use yii\helpers\Html;
use yii\widgets\ListView;

/** @var yii\web\View $this */
/** @var common\models\Category $category */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Posts in Category: ' . Html::encode($category->name);
$this->params['breadcrumbs'][] = ['label' => 'Posts', 'url' => ['post/index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="category-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <?php if ($dataProvider->getTotalCount() > 0): ?>
        <?= ListView::widget([
            'dataProvider' => $dataProvider,
            'itemOptions' => ['class' => 'item'],
            'itemView' => '@frontend/views/post/_post_item', // Reuse the post item view
            'layout' => "{summary}\n<div class='row'>{items}</div>\n{pager}",
            'pager' => [
                'options' => ['class' => 'pagination justify-content-center'],
                'linkContainerOptions' => ['class' => 'page-item'],
                'linkOptions' => ['class' => 'page-link'],
                'disabledListItemSubTagOptions' => ['tag' => 'a', 'class' => 'page-link'],
            ],
        ]) ?>
    <?php else: ?>
        <p>No posts found in this category.</p>
    <?php endif; ?>

</div>
