<?php

use yii\helpers\Html;
use yii\widgets\ListView;
use yii\widgets\Pjax; // Optional: for AJAX-based filtering/pagination

/** @var yii\web\View $this */
/** @var frontend\models\PostSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Posts';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="post-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <?php if (Yii::$app->user->can('createPost')): ?>
        <p>
            <?= Html::a('Create Post', ['create'], ['class' => 'btn btn-success']) ?>
        </p>
    <?php endif; ?>

    <?php Pjax::begin(); // Optional: Wrap ListView and Search Form for AJAX updates ?>

    <?php  echo $this->render('_search', ['model' => $searchModel]); // Optional: If you create a _search.php partial for filter form ?>

    <?= ListView::widget([
        'dataProvider' => $dataProvider,
        'itemOptions' => ['class' => 'item'], // Class for each item container
        'itemView' => '_post_item', // The partial view for rendering each data item
        'layout' => "{summary}\n<div class='row'>{items}</div>\n{pager}", // Customize layout
        'pager' => [ // Customize pager
            'options' => ['class' => 'pagination justify-content-center'],
            'linkContainerOptions' => ['class' => 'page-item'],
            'linkOptions' => ['class' => 'page-link'],
            'disabledListItemSubTagOptions' => ['tag' => 'a', 'class' => 'page-link'],
        ],
    ]) ?>

    <?php Pjax::end(); // Optional ?>

</div>
