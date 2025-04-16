<?php

//phpinfo();

/** @var yii\web\View $this */
/** @var string $content */

use app\assets\AppAsset;
use app\widgets\Alert;
use yii\bootstrap5\Breadcrumbs;
use yii\bootstrap5\Html;
use yii\bootstrap5\Nav;
use yii\bootstrap5\NavBar;

AppAsset::register($this);


$this->registerCsrfMetaTags();
$this->registerMetaTag(['charset' => Yii::$app->charset], 'charset');
$this->registerMetaTag(['name' => 'viewport', 'content' => 'width=device-width, initial-scale=1, shrink-to-fit=no']);
$this->registerMetaTag(['name' => 'description', 'content' => $this->params['meta_description'] ?? '']);
$this->registerMetaTag(['name' => 'keywords', 'content' => $this->params['meta_keywords'] ?? '']);
$this->registerLinkTag(['rel' => 'icon', 'type' => 'image/x-icon', 'href' => Yii::getAlias('@web/favicon.ico')]);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>" class="h-100">
<head>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        /* Navbar Styles */
        .navbar {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            background: #793657;
            padding: 15px 30px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            z-index: 1000;
        }
        .navbar-title {
            color: white;
            font-size: 1.5rem;
            font-weight: bold;
        }
        .profile-menu {
            position: relative;
        }
        .profile-icon {
            color: white;
            font-size: 1.5rem;
            cursor: pointer;
        }
        .dropdown-content {
            display: none;
            position: absolute;
            right: 0;
            top: 50px;
            background: white;
            border: 1px solid #ddd;
            border-radius: 5px;
            padding: 10px;
            z-index: 1001;
        }
        .dropdown-content.active {
            display: block;
        }
        .dropdown-item {
            display: block;
            padding: 10px;
            color: #793657;
            text-decoration: none;
        }
        .dropdown-item:hover {
            background: #f5f5f5;
        }
    </style>
</head>
<body class="d-flex flex-column h-100">
<?php $this->beginBody() ?>

<?php
    NavBar::begin([
        /*'brandLabel' => Yii::$app->name,
        'brandUrl' => Yii::$app->homeUrl,*/
        'options' => ['class' => 'navbar-expand-md navbar-dark bg-dark fixed-top']
    ]);
    echo Nav::widget([
        'options' => ['class' => 'navbar-nav'],
        'items' => [
            /*
            ['label' => 'Anasayfa', 'url' => ['/site/index']],
            ['label' => 'Hakkında', 'url' => ['/site/about']],
            ['label' => 'İletişim', 'url' => ['/site/contact']],
            */
            Yii::$app->user->isGuest
                ? ['label' => '', 'url' => ['/site/login']]
                : '<li class="nav-item">'
                    . Html::beginForm(['/site/logout'])
                    . Html::submitButton(
                        'Logout (' . Yii::$app->user->identity->username . ')',
                        ['class' => 'nav-link btn btn-link logout']
                    )
                    . Html::endForm()
                    . '</li>'
        ]
    ]);
    NavBar::end();
    ?>

<header id="header">
    <nav class="navbar">
        <div class="navbar-title"><?= Html::encode($this->title) ?></div>
        <?php if (!(Yii::$app->controller->id === 'site' && (Yii::$app->controller->action->id === 'index' || Yii::$app->controller->action->id === 'sifreunutma'))): ?>
            <div class="profile-menu" style="display: flex; align-items: center;">
                <div id="roleIndicator" style="color: white; margin-right: 15px; font-weight: bold;">Bilgi İşlem Daire Başkanlığı</div>
                <div class="profile-icon" onclick="toggleDropdown()">
                    <i class="fas fa-user-circle"></i>
                </div>
                <div class="dropdown-content" id="roleDropdown">
                    <span class="dropdown-item" style="color: black; font-weight: bold; pointer-events: none;">Tolga Kuyucuk</span>
                    <a href="/index.php" class="dropdown-item">Çıkış Yap</a>
                    <div class="dropdown-item" style="cursor: pointer;" onclick="toggleRoleOptions()">Birim Değiştir</div>
                    <div id="roleOptions" style="display: none; margin-top: 10px;">
                        <a href="javascript:void(0);" class="dropdown-item" onclick="setRoleFilter('Bilgi İşlem Daire Başkanlığı'); updateRoleIndicator('Bilgi İşlem Daire Başkanlığı')">Bilgi İşlem Daire Başkanlığı</a>
                        <a href="javascript:void(0);" class="dropdown-item" onclick="setRoleFilter('Erasmus Koordinatörlüğü'); updateRoleIndicator('Erasmus Koordinatörlüğü')">Erasmus Koordinatörlüğü</a>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    </nav>
</header>

<script>
    function updateRoleIndicator(role) {
        const roleIndicator = document.getElementById('roleIndicator');
        if (roleIndicator) {
            roleIndicator.textContent = role;
        }
    }
</script>

<script>
    function setRoleFilter(role) {
        const urlParams = new URLSearchParams(window.location.search);
        urlParams.set('role', role);
        window.history.replaceState({}, '', `${window.location.pathname}?${urlParams}`);
        // Trigger any necessary JavaScript or AJAX to update the filter
        if (typeof applyFilters === 'function') {
            applyFilters(); // Assuming there's a function to reapply filters
        }
    }
</script>

<script>
    function toggleRoleOptions() {
        const roleOptions = document.getElementById('roleOptions');
        roleOptions.style.display = roleOptions.style.display === 'none' || roleOptions.style.display === '' ? 'block' : 'none';
    }
</script>


<script>
    function toggleDropdown() {
        const dropdown = document.getElementById('roleDropdown');
        dropdown.classList.toggle('active');
    }

    // Close dropdown when clicking outside
    window.onclick = function(event) {
        if (!event.target.closest('.profile-menu')) {
            const dropdown = document.getElementById('roleDropdown');
            if (dropdown) dropdown.classList.remove('active');
        }
    };
</script>

<main id="main" class="flex-shrink-0" role="main">
    <div class="container">
        <?php if (!empty($this->params['breadcrumbs'])): ?>
            <?= Breadcrumbs::widget(['links' => $this->params['breadcrumbs']]) ?>
        <?php endif ?>
        <?= Alert::widget() ?>
        <?= $content ?>
    </div>
</main>

<footer id="footer" class="mt-auto py-3 bg-light">
    <div class="container">
        <div class="row text-muted">
            <div class="col-md-6 text-center text-md-start">&copy; Ankara Sosyal Bilimler Üniversitesi Bilgi İşlem Daire <?= date('Y') ?></div>
        </div>
    </div>
</footer>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>

