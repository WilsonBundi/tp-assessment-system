<?php

/** @var \yii\web\View $this */
/** @var string $content */

use backend\assets\AppAsset;
use common\widgets\Alert;
use yii\bootstrap5\Breadcrumbs;
use yii\bootstrap5\Html;
use yii\bootstrap5\Nav;
use yii\bootstrap5\NavBar;

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
            'class' => 'navbar navbar-expand-md navbar-dark fixed-top',
            'style' => 'background: linear-gradient(90deg, #2C3E50 0%, #3498DB 100%); box-shadow: 0 2px 8px rgba(0,0,0,0.15);',
        ],
    ]);
    $menuItems = [
        ['label' => '🏠 Dashboard', 'url' => ['/site/index']],
    ];
    if (!Yii::$app->user->isGuest) {
        $menuItems[] = ['label' => '📋 Assessments', 'url' => ['/assessment/index']];
        $menuItems[] = ['label' => '📊 Reports', 'url' => ['/report/index']];
        $menuItems[] = ['label' => '🔔 Notifications', 'url' => ['/notification/index']];
    }
    if (Yii::$app->user->isGuest) {
        $menuItems[] = ['label' => 'Login', 'url' => ['/site/login']];
    }     
    echo Nav::widget([
        'options' => ['class' => 'navbar-nav me-auto mb-2 mb-md-0'],
        'items' => $menuItems,
    ]);
    if (Yii::$app->user->isGuest) {
        echo Html::tag('div',Html::a('Login',['/site/login'],['class' => ['btn btn-light login text-decoration-none fw-bold']]),['class' => ['d-flex']]);
    } else {
        echo Html::beginForm(['/site/logout'], 'post', ['class' => 'd-flex'])
            . Html::submitButton(
                '👤 ' . Yii::$app->user->identity->username . ' (Logout)',
                ['class' => 'btn btn-light logout text-decoration-none fw-bold']
            )
            . Html::endForm();
    }
    NavBar::end();
    ?>
</header>

<main role="main" class="flex-shrink-0" style="margin-top: 90px; padding: 40px 0; background-color: #F8F9FA;">
    <div class="container">
        <style>
            .breadcrumb {
                background-color: transparent;
                padding: 0 0 20px 0;
                margin-bottom: 0;
                font-size: 0.95rem;
            }
            .breadcrumb-item {
                color: #3498DB;
            }
            .breadcrumb-item a {
                color: #3498DB;
                text-decoration: none;
                font-weight: 500;
            }
            .breadcrumb-item a:hover {
                color: #2874A6;
                text-decoration: underline;
            }
            .breadcrumb-item.active {
                color: #2C3E50;
                font-weight: 600;
            }
        </style>
        <?= Breadcrumbs::widget([
            'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
        ]) ?>
        <?= Alert::widget() ?>
        <?= $content ?>
    </div>
</main>

<footer class="footer mt-auto py-4" style="background-color: #2C3E50; color: white; margin-top: 40px;">
    <div class="container">
        <p class="float-start mb-0" style="font-size: 0.9rem;">
            &copy; <?= Html::encode(Yii::$app->name) ?> <?= date('Y') ?> | University of Nairobi Faculty of Education
        </p>
        <p class="float-end mb-0" style="font-size: 0.85rem;">Teaching Practice Assessment System v1.0</p>
    </div>
</footer>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage();
