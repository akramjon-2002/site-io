<?php

use yii\helpers\Html;
use yii\bootstrap5\ActiveForm;
use common\models\Category;
use yii\helpers\ArrayHelper;

/** @var yii\web\View $this */
/** @var frontend\models\PostSearch $model */
/** @var yii\bootstrap5\ActiveForm $form */
?>

<div class="post-search mb-4">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
        'options' => [
            'data-pjax' => 1 // Optional: if using Pjax for the ListView
        ],
    ]); ?>

    <div class="row">
        <div class="col-md-4">
            <?= $form->field($model, 'title') ?>
        </div>
        <div class="col-md-4">
            <?= $form->field($model, 'category_id')->dropDownList(
                ArrayHelper::map(Category::find()->orderBy('name')->asArray()->all(), 'id', 'name'),
                ['prompt' => 'All Categories']
            ) ?>
        </div>
        <?php // echo $form->field($model, 'content') ?>
        <?php // echo $form->field($model, 'user_id') // Or search by author name ?>
        <?php // echo $form->field($model, 'created_at') ?>
        <?php // echo $form->field($model, 'updated_at') ?>
    </div>
    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Reset', ['index'], ['class' => 'btn btn-outline-secondary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
