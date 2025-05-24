<?php

use yii\helpers\Html;
use yii\bootstrap5\ActiveForm;
use common\models\Category;
use yii\helpers\ArrayHelper;

/** @var yii\web\View $this */
/** @var common\models\Post $model */
/** @var yii\bootstrap5\ActiveForm $form */
/** @var string $tagValues */

?>

<div class="post-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'content')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'category_id')->dropDownList(
        ArrayHelper::map(Category::find()->orderBy('name')->asArray()->all(), 'id', 'name'),
        ['prompt' => 'Select Category']
    ) ?>

    <div class="mb-3">
        <label for="tagValues" class="form-label">Tags (comma-separated)</label>
        <?= Html::textInput('tagValues', $tagValues, ['class' => 'form-control', 'id' => 'tagValues']) ?>
        <div class="form-text">Enter tags separated by commas. For example: yii2, programming, blog</div>
    </div>


    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
