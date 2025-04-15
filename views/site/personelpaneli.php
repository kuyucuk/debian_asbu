<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <title>Personel Paneli</title>
    <link rel="stylesheet" href="styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar">
        <div class="navbar-title">Personel Paneli</div>
        <div class="profile-menu">
            <div class="profile-icon" onclick="toggleDropdown()">
                <i class="fas fa-user-circle"></i>
            </div>
            <div class="dropdown-content" id="dropdownMenu">
                <a href="#" class="dropdown-item">Rol Değiştir</a>
                <a href="index.php" class="dropdown-item">Çıkış Yap</a>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <div class="iframe-container">
        <iframe src="/index.php?r=self-assessment" frameborder="0" width="100%" height="100%"></iframe>
    </div>

    <script>
        function toggleDropdown() {
            const menu = document.getElementById('dropdownMenu');
            menu.classList.toggle('active');
        }

        // Close dropdown when clicking outside
        window.onclick = function(event) {
            if (!event.target.closest('.profile-menu')) {
                const menus = document.getElementsByClassName("dropdown-content");
                for (let i = 0; i < menus.length; i++) {
                    menus[i].classList.remove('active');
                }
            }
        }
    </script>
</body>
</html>

<?php
$css = <<<CSS
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
    box-shadow: 0 2px 5px rgba(0,0,0,0.1);
}

.navbar-title {
    color: white;
    font-size: 24px;
    font-weight: bold;
}

.profile-menu {
    position: relative;
    display: inline-block;
}

.profile-icon {
    color: white;
    font-size: 32px;
    cursor: pointer;
    transition: transform 0.3s;
}

.profile-icon:hover {
    transform: scale(1.1);
}

/* Dropdown Styles */
.dropdown-content {
    position: absolute;
    right: 0;
    top: 100%;
    background: white;
    min-width: 160px;
    box-shadow: 0 8px 16px rgba(0,0,0,0.1);
    border-radius: 5px;
    max-height: 0;
    overflow: hidden;
    opacity: 0;
    transition: all 0.3s ease;
    z-index: 1001;
}

.dropdown-content.active {
    max-height: 200px;
    opacity: 1;
    margin-top: 5px;
}

.dropdown-item {
    color: #333;
    padding: 12px 16px;
    text-decoration: none;
    display: block;
    transition: background 0.3s;
}

.dropdown-item:hover {
    background: #f5f5f5;
}

/* Iframe Container */
.iframe-container {
    margin-top: 70px; /* Adjust for fixed navbar */
    width: 100%;
    height: calc(100vh - 70px); /* Full height minus navbar height */
    overflow: hidden;
}
CSS;

$this->registerCss($css);
?>
