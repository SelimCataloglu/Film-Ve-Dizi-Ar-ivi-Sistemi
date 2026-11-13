<?php
/**
 * FilmArşivX — Ana Sayfa / Arşiv Paneli (index.php)
 * Öğrenci: Muhammed Selim | İnternet Programcılığı I
 *
 * Özellikler:
 *  - Kayıt listeleme (foreach döngüsü)
 *  - Durum filtresi (GET yöntemi)
 *  - Metin arama (GET yöntemi)
 *  - İstatistik sayaçları (kullanıcı tanımlı fonksiyonlar)
 *  - Flash bildirim mesajları
 *  - Örnek veri sıfırlama (POST yöntemi)
 */

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once 'functions.php';

// Örnek kayıtları yeniden yükleme (POST yöntemi)
if (($_SERVER['REQUEST_METHOD'] ?? '') === 'POST' && ($_POST['aksiyon'] ?? '') === 'sifirla') {
    $_SESSION['filmler'] = varsayilanFilmleriGetir();
    $_SESSION['bildirim'] = ['tur' => 'basari', 'mesaj' => '5 örnek kayıt yeniden yüklendi.'];
    header('Location: index.php');
    exit;
}

// Oturum yoksa varsayılan verilerle başlat
if (!isset($_SESSION['filmler']) || !is_array($_SESSION['filmler'])) {
    $_SESSION['filmler'] = varsayilanFilmleriGetir();
}

// Flash bildirim
$bildirim = null;
if (isset($_SESSION['bildirim'])) {
    $bildirim = $_SESSION['bildirim'];
    unset($_SESSION['bildirim']);
}

// İstatistik sayaçları (kullanıcı tanımlı fonksiyonlar — Teknik Kural 8)
$toplam_kayit    = count($_SESSION['filmler']);
$izlenen_sayisi  = durumSayisiHesapla($_SESSION['filmler'], 'İzlendi');
$izlenecek_sayisi = durumSayisiHesapla($_SESSION['filmler'], 'İzlenecek');
$ortalama_puan   = ortalamaPuanHesapla($_SESSION['filmler']);

// Filtreleme ve arama parametreleri (GET yöntemi — Teknik Kural 5)
$secilen_filtre = $_GET['durum'] ?? 'tumu';
$arama_terimi   = trim($_GET['q'] ?? '');

// foreach ile filtreleme (Teknik Kural 6)
$filtrelenmis = [];
foreach ($_SESSION['filmler'] as $idx => $film) {
    // Durum filtresi
    if (($secilen_filtre === 'İzlendi' || $secilen_filtre === 'İzlenecek')
        && ($film['durum'] ?? '') !== $secilen_filtre) {
        continue;
    }
    // Arama filtresi (mb_stripos — büyük/küçük harf duyarsız, UTF-8 uyumlu)
    if ($arama_terimi !== '') {
        $esl = mb_stripos($film['baslik'] ?? '', $arama_terimi) !== false
            || mb_stripos($film['aciklama'] ?? '', $arama_terimi) !== false
            || mb_stripos($film['tur'] ?? '', $arama_terimi) !== false
            || mb_stripos($film['yonetmen'] ?? '', $arama_terimi) !== false;
        if (!$esl) continue;
    }
    $filtrelenmis[$idx] = $film;
}

// Topbar başlık metni
$page_title   = 'Arşiv Paneli';
$topbar_title = '<span>Arşiv</span> Paneli';
$active_nav   = 'index';
include 'partials/header.php';
?>

<!-- =========================================================
     HERO BANNER
     ========================================================= -->
<div class="hero-banner">
    <div class="hero-eyebrow">🎬 Kişisel Koleksiyonum</div>
    <h1 class="hero-title">Film ve Dizi<br><span>Arşiv Sistemi</span></h1>
    <p class="hero-subtitle">
        İzlediğin ve izlemek istediğin tüm yapımları takip et, filtrele, ara ve yönet.
        PHP &amp; Session ile çalışan dinamik arşiv paneli.
    </p>
</div>

<!-- Flash Bildirim -->
<?php if ($bildirim): ?>
    <div class="alert alert-<?php echo $bildirim['tur'] === 'basari' ? 'success' : 'danger'; ?>">
        <div class="alert-icon"><?php echo $bildirim['tur'] === 'basari' ? '✓' : '⚠️'; ?></div>
        <div class="alert-body"><?php echo $bildirim['mesaj']; ?></div>
    </div>
<?php endif; ?>

<!-- =========================================================
     İSTATİSTİK KARTLARI (Madde 3.7)
     ========================================================= -->
<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-icon icon-blue">🎞️</div>
        <div class="stat-info">
            <span class="stat-num num-blue"><?php echo $toplam_kayit; ?></span>
            <span class="stat-lbl">Toplam Kayıt</span>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon icon-green">✅</div>
        <div class="stat-info">
            <span class="stat-num num-green"><?php echo $izlenen_sayisi; ?></span>
            <span class="stat-lbl">İzlenenler</span>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon icon-amber">⏳</div>
        <div class="stat-info">
            <span class="stat-num num-amber"><?php echo $izlenecek_sayisi; ?></span>
            <span class="stat-lbl">İzlenecekler</span>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon icon-cyan">⭐</div>
        <div class="stat-info">
            <span class="stat-num num-cyan"><?php echo $ortalama_puan; ?></span>
            <span class="stat-lbl">Ort. Puan /10</span>
        </div>
    </div>
</div>

<!-- =========================================================
     KAYIT LİSTESİ
     ========================================================= -->
<div class="content-panel">

    <!-- Araç Çubuğu -->
    <div class="panel-toolbar">
        <div class="toolbar-left">
            <h2>Koleksiyon</h2>
            <div class="sub">
                <?php echo count($filtrelenmis); ?> kayıt görüntüleniyor
                <?php if ($secilen_filtre !== 'tumu' || !empty($arama_terimi)): ?>
                    &mdash; <em>Filtreli Görünüm</em>
                <?php endif; ?>
            </div>
        </div>

        <!-- Durum Filtre Tabları (GET yöntemi) -->
        <div class="filter-tabs">
            <a href="index.php?durum=tumu<?php echo !empty($arama_terimi) ? '&q='.urlencode($arama_terimi) : ''; ?>"
               class="filter-tab <?php echo $secilen_filtre === 'tumu' ? 'active' : ''; ?>">
                Tümü (<?php echo $toplam_kayit; ?>)
            </a>
            <a href="index.php?durum=İzlendi<?php echo !empty($arama_terimi) ? '&q='.urlencode($arama_terimi) : ''; ?>"
               class="filter-tab <?php echo $secilen_filtre === 'İzlendi' ? 'active' : ''; ?>">
                İzlendi (<?php echo $izlenen_sayisi; ?>)
            </a>
            <a href="index.php?durum=İzlenecek<?php echo !empty($arama_terimi) ? '&q='.urlencode($arama_terimi) : ''; ?>"
               class="filter-tab <?php echo $secilen_filtre === 'İzlenecek' ? 'active' : ''; ?>">
                İzlenecek (<?php echo $izlenecek_sayisi; ?>)
            </a>
        </div>
    </div>

    <!-- Kayıt Listesi (foreach döngüsü — Teknik Kural 6) -->
    <?php if (empty($filtrelenmis)): ?>
        <div class="empty-box">
            <div class="empty-icon-big">🔍</div>
            <h3>Kayıt Bulunamadı</h3>
            <p>Arama veya filtre kriterlerinize uyan kayıt yok. Filtreyi sıfırlayın ya da yeni bir kayıt ekleyin.</p>
            <div class="empty-actions">
                <a href="index.php" class="btn-secondary">Filtreyi Sıfırla</a>
                <a href="ekle.php" class="btn-primary">+ Yeni Kayıt Ekle</a>
            </div>
        </div>
    <?php else: ?>
        <div class="movies-list">
            <?php foreach ($filtrelenmis as $orijinal_idx => $film): ?>
                <article class="movie-row">

                    <!-- Sol Renk Şeridi (Format + Yıl) -->
                    <div class="movie-color-strip">
                        <span class="movie-format-icon">
                            <?php echo formatTipiSimge($film['tur_tipi'] ?? 'Film'); ?>
                        </span>
                        <span class="movie-year-strip">
                            <?php echo guvenliCikti($film['yil'] ?? ''); ?>
                        </span>
                    </div>

                    <!-- Ana Bilgiler -->
                    <div class="movie-info">
                        <div class="movie-info-top">
                            <div>
                                <a href="detay.php?id=<?php echo $orijinal_idx; ?>" class="movie-title-link">
                                    <?php echo guvenliCikti($film['baslik'] ?? 'İsimsiz'); ?>
                                </a>
                                <!-- Tür Etiketleri -->
                                <div class="movie-tags">
                                    <?php
                                    if (!empty($film['tur'])) {
                                        $turler = explode('/', $film['tur']);
                                        foreach ($turler as $t):
                                            $t = trim($t);
                                            if (!empty($t)):
                                    ?>
                                        <span class="tag"><?php echo guvenliCikti($t); ?></span>
                                    <?php
                                            endif;
                                        endforeach;
                                    }
                                    ?>
                                </div>
                            </div>
                            <?php echo durumRozetiUret($film['durum'] ?? 'İzlenecek'); ?>
                        </div>

                        <!-- Açıklama -->
                        <p class="movie-desc"><?php echo guvenliCikti($film['aciklama'] ?? ''); ?></p>

                        <!-- Alt Bilgi + Butonlar -->
                        <div class="movie-info-bottom">
                            <div class="movie-meta">
                                <?php if (!empty($film['yonetmen']) && $film['yonetmen'] !== 'Belirtilmedi'): ?>
                                    <span class="meta-item">🎬 <?php echo guvenliCikti($film['yonetmen']); ?></span>
                                <?php endif; ?>
                                <?php if (!empty($film['puan']) && $film['puan'] !== '—'): ?>
                                    <span class="rating-pill">⭐ <?php echo guvenliCikti((string)$film['puan']); ?></span>
                                <?php endif; ?>
                                <span class="id-label">#<?php echo $orijinal_idx + 1; ?></span>
                            </div>

                            <div class="movie-actions">
                                <a href="detay.php?id=<?php echo $orijinal_idx; ?>" class="btn-detail">
                                    Detay →
                                </a>
                                <!-- Silme işlemi POST yöntemiyle (Teknik Kural 5) -->
                                <form action="sil.php" method="POST" class="d-inline"
                                      onsubmit="return confirm('\"<?php echo addslashes(guvenliCikti($film['baslik'])); ?>\" silinsin mi?');">
                                    <input type="hidden" name="id" value="<?php echo $orijinal_idx; ?>">
                                    <button type="submit" class="btn-delete">🗑️ Sil</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </article>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

    <!-- Alt Bar: Sıfırlama -->
    <div class="bottom-bar">
        <span class="bottom-bar-note">
            Sunum için: Kayıtları sildiğinizde aşağıdaki butonla 5 örnek veriyi geri yükleyebilirsiniz.
        </span>
        <form action="index.php" method="POST"
              onsubmit="return confirm('Tüm kayıtlar silinerek 5 örnek kayıt yüklenir. Devam edilsin mi?');">
            <input type="hidden" name="aksiyon" value="sifirla">
            <button type="submit" class="btn-outline">↺ Örnek Verileri Yükle</button>
        </form>
    </div>

</div><!-- /content-panel -->

<?php include 'partials/footer.php'; ?>
