<?php

/** @var \yii\web\View $this */
/** @var string $content */

use frontend\assets\AppAsset; // Assuming AppAsset exists or will be created
use common\widgets\Alert; // Standard Yii2 alert widget
use yii\bootstrap5\Breadcrumbs;
use yii\bootstrap5\Html;
use yii\bootstrap5\Nav;
use yii\bootstrap5\NavBar;

// It's good practice to register AppAsset. If it doesn't exist, this might cause an error.
// For now, I'll assume it's either present or will be added.
// If not, it can be temporarily commented out.
AppAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>" class="h-100">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <?php $this->registerCsrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>
<body class="d-flex flex-column h-100">
<?php $this->beginBody() ?>

<header>
    <?php
    NavBar::begin([
        'brandLabel' => Yii::$app->name,
        'brandUrl' => Yii::$app->homeUrl,
        'options' => [
            'class' => 'navbar navbar-expand-md navbar-dark bg-dark fixed-top',
        ],
    ]);
    $menuItems = [
        ['label' => 'Home', 'url' => ['/site/index']],
        ['label' => 'Posts', 'url' => ['/post/index']],
        // ['label' => 'About', 'url' => ['/site/about']], // Example
        // ['label' => 'Contact', 'url' => ['/site/contact']], // Example
    ];
    if (Yii::$app->user->isGuest) {
        $menuItems[] = ['label' => 'Signup', 'url' => ['/site/signup']];
        $menuItems[] = ['label' => 'Login', 'url' => ['/site/login']];
    } else {
        $menuItems[] = '<li>'
            . Html::beginForm(['/site/logout'], 'post', ['class' => 'form-inline'])
            . Html::submitButton(
                'Logout (' . Yii::$app->user->identity->username . ')',
                ['class' => 'btn btn-link logout']
            )
            . Html::endForm()
            . '</li>';
    }
    echo Nav::widget([
        'options' => ['class' => 'navbar-nav ms-auto'],
        'items' => $menuItems,
    ]);
    NavBar::end();
    ?>
</header>

<main role="main" class="flex-shrink-0">
    <div class="container" style="padding-top: 70px;">
        <?= Breadcrumbs::widget([
            'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
        ]) ?>
        <?= Alert::widget() // Standard Yii2 alert widget for flash messages ?>
        
        <div class="row">
            <div class="col-md-9">
                <?= $content ?>
            </div>
            <div class="col-md-3">
                <?= $this->render('@frontend/views/category/_list') ?>
                
                <?php
                // Placeholder for other sidebar widgets, e.g., Tag cloud
                /*
                if (class_exists('frontend\widgets\TagCloud')) { // Check if widget exists
                    echo frontend\widgets\TagCloud::widget([
                        'options' => ['class' => 'tag-cloud-widget mt-4']
                    ]);
                }
                */
                ?>
            </div>
        </div>
    </div>
</main>

<footer class="footer mt-auto py-3 bg-light">
    <div class="container">
        <p class="float-start">&copy; <?= Html::encode(Yii::$app->name) ?> <?= date('Y') ?></p>
        <p class="float-end"><?= Yii::powered() ?></p>
    </div>
</footer>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
