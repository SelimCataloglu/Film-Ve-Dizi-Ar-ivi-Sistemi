<?php
/**
 * FilmArşivX — Ortak HTML Başlık (Partial)
 * Öğrenci: Muhammed Selim | İnternet Programcılığı I
 *
 * Kullanım: include 'partials/header.php';
 * Değişkenler: $page_title, $active_nav, $topbar_title
 */
?>
<!DOCTYPE html>
<html lang="tr" data-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($page_title) ? guvenliCikti($page_title) . ' — FilmArşivX' : 'FilmArşivX'; ?></title>
    <!-- Tema Seçimini Sayfa Yüklenirken Anında Uygula (Flicker Önleyici) -->
    <script>
        (function() {
            var kayitliTema = localStorage.getItem('filmarxiv_theme') || 'light';
            document.documentElement.setAttribute('data-theme', kayitliTema);
        })();
    </script>
    <link rel="stylesheet" href="<?php echo isset($assets_prefix) ? $assets_prefix : ''; ?>assets/style.css">
</head>
<body>
<div class="layout-wrapper">

    <!-- ==================== SIDEBAR ==================== -->
    <?php
    $sb_toplam    = isset($_SESSION['filmler']) ? count($_SESSION['filmler']) : 0;
    $sb_izlendi   = isset($_SESSION['filmler']) ? durumSayisiHesapla($_SESSION['filmler'], 'İzlendi') : 0;
    $sb_izlenecek = isset($_SESSION['filmler']) ? durumSayisiHesapla($_SESSION['filmler'], 'İzlenecek') : 0;
    $sb_ortalama  = isset($_SESSION['filmler']) ? ortalamaPuanHesapla($_SESSION['filmler']) : '—';
    ?>
    <aside class="sidebar">
        <!-- Logo -->
        <div class="sidebar-logo">
            <div class="logo-icon">🎬</div>
            <div class="logo-text">FilmArşivX</div>
            <div class="logo-sub">Sinema Arşiv Sistemi</div>
        </div>

        <!-- Nav Links -->
        <nav class="sidebar-nav">
            <div class="nav-label">Ana Menü</div>

            <a href="index.php" class="nav-item <?php echo (($active_nav ?? '') === 'index') ? 'active' : ''; ?>">
                <span class="nav-icon">🏠</span>
                <span>Arşiv Paneli</span>
                <span class="nav-badge"><?php echo $sb_toplam; ?></span>
            </a>

            <a href="ekle.php" class="nav-item <?php echo (($active_nav ?? '') === 'ekle') ? 'active' : ''; ?>">
                <span class="nav-icon">➕</span>
                <span>Yeni Kayıt Ekle</span>
            </a>

            <a href="istatistik.php" class="nav-item <?php echo (($active_nav ?? '') === 'istatistik') ? 'active' : ''; ?>">
                <span class="nav-icon">📊</span>
                <span>İstatistikler</span>
            </a>

            <div class="nav-label">Filtreler</div>

            <a href="index.php?durum=tumu" class="nav-item">
                <span class="nav-icon">📋</span>
                <span>Tüm Kayıtlar</span>
                <span class="nav-badge"><?php echo $sb_toplam; ?></span>
            </a>

            <a href="index.php?durum=İzlendi" class="nav-item">
                <span class="nav-icon">✅</span>
                <span>İzlediklerim</span>
                <span class="nav-badge"><?php echo $sb_izlendi; ?></span>
            </a>

            <a href="index.php?durum=İzlenecek" class="nav-item">
                <span class="nav-icon">⏳</span>
                <span>İzleyeceklerim</span>
                <span class="nav-badge"><?php echo $sb_izlenecek; ?></span>
            </a>
        </nav>

        <!-- Mini İstatistik Kutuları -->
        <div class="sidebar-stats">
            <div class="sidebar-stat-row">
                <div class="sidebar-stat-card">
                    <span class="sidebar-stat-val"><?php echo $sb_toplam; ?></span>
                    <span class="sidebar-stat-lbl">Toplam</span>
                </div>
                <div class="sidebar-stat-card">
                    <span class="sidebar-stat-val"><?php echo $sb_ortalama; ?></span>
                    <span class="sidebar-stat-lbl">Ort. Puan</span>
                </div>
            </div>
        </div>

        <!-- Sidebar Alt Bilgi -->
        <div class="sidebar-footer">
            <strong>Öğrenci:</strong> Muhammed Selim<br>
            İnternet Programcılığı I<br>
            PHP Proje Ödevi &bull; 2026
        </div>
    </aside>

    <!-- ==================== MAIN ==================== -->
    <div class="main-content">

        <!-- TOP BAR -->
        <header class="topbar">
            <div class="topbar-title">
                <?php echo isset($topbar_title) ? $topbar_title : '<span>Film</span>ArşivX'; ?>
            </div>

            <?php if (!isset($hide_search) || !$hide_search): ?>
            <!-- Arama Formu (GET yöntemi) -->
            <form action="index.php" method="GET" class="topbar-search">
                <?php if (isset($_GET['durum']) && $_GET['durum'] !== 'tumu'): ?>
                    <input type="hidden" name="durum" value="<?php echo guvenliCikti($_GET['durum']); ?>">
                <?php endif; ?>
                <div class="search-wrap">
                    <span class="search-ico">🔍</span>
                    <input
                        type="text"
                        name="q"
                        class="search-field"
                        placeholder="Film, dizi veya yönetmen ara..."
                        value="<?php echo guvenliCikti($_GET['q'] ?? ''); ?>"
                    >
                    <?php if (!empty($_GET['q'])): ?>
                        <a href="index.php" class="btn-clear-x" title="Aramayı Temizle">✕</a>
                    <?php endif; ?>
                </div>
                <button type="submit" class="btn-search-go">Ara</button>
            </form>
            <?php endif; ?>

            <!-- Aydınlık / Karanlık Mod Değiştirici Buton -->
            <button id="theme-toggle" class="btn-theme-toggle" type="button" title="Temayı Değiştir (Açık / Koyu)">
                <span class="theme-icon">🌙</span>
                <span class="theme-text">Karanlık Mod</span>
            </button>

            <a href="ekle.php" class="btn-add-topbar">+ Yeni Kayıt</a>
        </header>

        <!-- PAGE BODY (footer.php içinde kapatılır) -->
        <div class="page-body">
