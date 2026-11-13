<?php
/**
 * FilmArşivX — Film/Dizi Detay Sayfası (detay.php)
 * Öğrenci: Muhammed Selim | İnternet Programcılığı I
 *
 * URL: detay.php?id=0 (GET yöntemiyle ID alınır)
 * Görevi: Seçilen kaydın tüm bilgilerini şık bir kartla sunar.
 */

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once 'functions.php';

// Oturum yoksa yükle
if (!isset($_SESSION['filmler']) || !is_array($_SESSION['filmler'])) {
    $_SESSION['filmler'] = varsayilanFilmleriGetir();
}

// ID parametresini doğrula
$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

if ($id === null || $id === false || !isset($_SESSION['filmler'][$id])) {
    // Kayıt bulunamadıysa ana sayfaya yönlendir
    $_SESSION['bildirim'] = ['tur' => 'hata', 'mesaj' => 'Kayıt bulunamadı.'];
    header('Location: index.php');
    exit;
}

$film = $_SESSION['filmler'][$id];

$page_title   = guvenliCikti($film['baslik'] ?? 'Detay');
$topbar_title = '<span>' . guvenliCikti($film['baslik'] ?? 'Detay') . '</span>';
$active_nav   = 'index';
$hide_search  = true;
include 'partials/header.php';
?>

<!-- =========================================================
     DETAY HERO
     ========================================================= -->
<div class="detail-hero">

    <!-- Poster (Simgesel) -->
    <div class="detail-poster">
        <span><?php echo formatTipiSimge($film['tur_tipi'] ?? 'Film'); ?></span>
        <small><?php echo guvenliCikti($film['tur_tipi'] ?? 'Film'); ?></small>
    </div>

    <!-- Bilgiler -->
    <div class="detail-info">
        <div class="detail-eyebrow">
            <?php echo guvenliCikti($film['tur_tipi'] ?? 'Film'); ?> &bull; <?php echo guvenliCikti((string)($film['yil'] ?? '')); ?>
        </div>

        <h1 class="detail-title"><?php echo guvenliCikti($film['baslik'] ?? 'İsimsiz'); ?></h1>

        <!-- Durum ve Tür Rozetleri -->
        <div class="detail-badges">
            <?php echo durumRozetiUret($film['durum'] ?? 'İzlenecek'); ?>
            <?php
            if (!empty($film['tur'])) {
                $turler = explode('/', $film['tur']);
                foreach ($turler as $t) {
                    $t = trim($t);
                    if (!empty($t)) {
                        echo '<span class="tag">' . guvenliCikti($t) . '</span>';
                    }
                }
            }
            ?>
        </div>

        <!-- Puan -->
        <?php if (!empty($film['puan']) && $film['puan'] !== '—'): ?>
            <div class="detail-rating">
                <span>⭐</span>
                <span><?php echo guvenliCikti((string)$film['puan']); ?></span>
                <small style="font-size:12px; color: var(--gray-500); font-weight:400;">/10 Puan</small>
            </div>
        <?php endif; ?>

        <!-- Meta Bilgiler (2'li Izgara) -->
        <div class="detail-meta-grid">
            <div class="detail-meta-item">
                <span class="detail-meta-label">Yönetmen / Yapımcı</span>
                <span class="detail-meta-val">
                    <?php echo guvenliCikti($film['yonetmen'] ?? 'Belirtilmedi'); ?>
                </span>
            </div>
            <div class="detail-meta-item">
                <span class="detail-meta-label">Çıkış Yılı</span>
                <span class="detail-meta-val">
                    <?php echo guvenliCikti((string)($film['yil'] ?? '—')); ?>
                </span>
            </div>
            <div class="detail-meta-item">
                <span class="detail-meta-label">Format</span>
                <span class="detail-meta-val">
                    <?php echo formatTipiSimge($film['tur_tipi'] ?? 'Film'); ?>
                    <?php echo guvenliCikti($film['tur_tipi'] ?? 'Film'); ?>
                </span>
            </div>
            <div class="detail-meta-item">
                <span class="detail-meta-label">Arşiv ID</span>
                <span class="detail-meta-val" style="font-family:monospace;">
                    #<?php echo $id + 1; ?>
                </span>
            </div>
        </div>

        <!-- Aksiyon Butonları -->
        <div class="detail-actions">
            <a href="index.php" class="btn-secondary">← Listeye Dön</a>

            <!-- Silme işlemi POST yöntemiyle (Teknik Kural 5) -->
            <form action="sil.php" method="POST" style="display:inline;"
                  onsubmit="return confirm('\"<?php echo addslashes(guvenliCikti($film['baslik'])); ?>\" silinsin mi?');">
                <input type="hidden" name="id" value="<?php echo $id; ?>">
                <button type="submit" class="btn-delete">🗑️ Arşivden Sil</button>
            </form>
        </div>
    </div>
</div>

<!-- =========================================================
     AÇIKLAMA KARTI
     ========================================================= -->
<div class="detail-desc-card">
    <h3>📖 Özet &amp; Açıklama</h3>
    <p><?php echo guvenliCikti($film['aciklama'] ?? 'Bu kayıt için açıklama girilmemiş.'); ?></p>
</div>

<!-- =========================================================
     BENZERİ YAPIMLARA GİT (Türe Göre Arama Kısayolu)
     ========================================================= -->
<?php
$tur_ilk = '';
if (!empty($film['tur'])) {
    $parca = explode('/', $film['tur']);
    $tur_ilk = trim($parca[0]);
}
?>
<?php if (!empty($tur_ilk)): ?>
<div class="detail-desc-card" style="margin-top:0;">
    <h3>🔗 Benzer Türdeki Yapımlar</h3>
    <p style="margin-bottom:14px;">
        Koleksiyonunuzdaki diğer <strong><?php echo guvenliCikti($tur_ilk); ?></strong>
        türü yapımları keşfetmek için:
    </p>
    <a href="index.php?q=<?php echo urlencode($tur_ilk); ?>" class="btn-detail">
        🔍 "<?php echo guvenliCikti($tur_ilk); ?>" Türünü Ara →
    </a>
</div>
<?php endif; ?>

<?php include 'partials/footer.php'; ?>
