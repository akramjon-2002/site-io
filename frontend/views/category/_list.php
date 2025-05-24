<?php

use common\models\Category;
use yii\helpers\Html;

/** @var yii\web\View $this */

$categories = Category::find()->orderBy('name')->all();
?>

<?php if ($categories): ?>
<div class="card categories-widget mb-4">
    <div class="card-header">
        <h5 class="card-title mb-0">Categories</h5>
    </div>
    <ul class="list-group list-group-flush">
        <?php foreach ($categories as $category): ?>
            <li class="list-group-item">
                <?= Html::a(Html::encode($category->name), ['category/view', 'id' => $category->id]) ?>
            </li>
        <?php endforeach; ?>
    </ul>
</div>
<?php endif; ?>
