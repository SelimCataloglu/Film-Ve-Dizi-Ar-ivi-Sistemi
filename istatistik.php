<?php
/**
 * FilmArşivX — İstatistik Sayfası (istatistik.php)
 * Öğrenci: Muhammed Selim | İnternet Programcılığı I
 *
 * Görevi: Arşivdeki kayıtlar üzerinde istatistiksel özet sunar.
 * Kullanıcı tanımlı fonksiyonlar ile hesaplama yapılır.
 */

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once 'functions.php';

if (!isset($_SESSION['filmler']) || !is_array($_SESSION['filmler'])) {
    $_SESSION['filmler'] = varsayilanFilmleriGetir();
}

$filmler = $_SESSION['filmler'];

// Temel İstatistikler (Kullanıcı Tanımlı Fonksiyonlar)
$toplam         = count($filmler);
$izlendi        = durumSayisiHesapla($filmler, 'İzlendi');
$izlenecek      = durumSayisiHesapla($filmler, 'İzlenecek');
$ortalama_puan  = ortalamaPuanHesapla($filmler);

// Yüzde hesapla (sıfıra bölme koruması)
$izlendi_yuzde  = $toplam > 0 ? round(($izlendi / $toplam) * 100) : 0;
$izlenecek_yuzde = $toplam > 0 ? round(($izlenecek / $toplam) * 100) : 0;

// Format türü dağılımı
$format_sayilari = [];
foreach ($filmler as $film) {
    $tip = $film['tur_tipi'] ?? 'Film';
    $format_sayilari[$tip] = ($format_sayilari[$tip] ?? 0) + 1;
}
arsort($format_sayilari);

// En yüksek puanlı 3 kayıt
$puanli_filmler = array_filter($filmler, fn($f) => !empty($f['puan']) && is_numeric($f['puan']));
usort($puanli_filmler, fn($a, $b) => floatval($b['puan']) <=> floatval($a['puan']));
$top3 = array_slice($puanli_filmler, 0, 3);

// Yılı en eski ve en yeni yapım
$yillar = array_filter(array_column($filmler, 'yil'), fn($y) => is_numeric($y));
$en_eski = !empty($yillar) ? min($yillar) : '—';
$en_yeni = !empty($yillar) ? max($yillar) : '—';

$page_title   = 'İstatistikler';
$topbar_title = '<span>Arşiv</span> İstatistikleri';
$active_nav   = 'istatistik';
$hide_search  = true;
include 'partials/header.php';
?>

<!-- =========================================================
     ÜST ÖZETLEYİCİ GRID
     ========================================================= -->
<div class="stats-grid" style="margin-bottom:24px;">
    <div class="stat-card">
        <div class="stat-icon icon-blue">🎞️</div>
        <div class="stat-info">
            <span class="stat-num num-blue"><?php echo $toplam; ?></span>
            <span class="stat-lbl">Toplam Kayıt</span>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon icon-green">✅</div>
        <div class="stat-info">
            <span class="stat-num num-green"><?php echo $izlendi; ?></span>
            <span class="stat-lbl">İzlenenler (%<?php echo $izlendi_yuzde; ?>)</span>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon icon-amber">⏳</div>
        <div class="stat-info">
            <span class="stat-num num-amber"><?php echo $izlenecek; ?></span>
            <span class="stat-lbl">İzlenecekler (%<?php echo $izlenecek_yuzde; ?>)</span>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon icon-cyan">⭐</div>
        <div class="stat-info">
            <span class="stat-num num-cyan"><?php echo $ortalama_puan; ?></span>
            <span class="stat-lbl">Ortalama Puan</span>
        </div>
    </div>
</div>

<!-- =========================================================
     ANA DETAY PANELLER
     ========================================================= -->
<div class="stats-page-grid">

    <!-- Panel 1: İzlenme Durumu Dağılımı -->
    <div class="stat-panel">
        <h3>📊 İzlenme Durumu Dağılımı</h3>

        <div class="progress-row">
            <span class="progress-label">İzlendi</span>
            <div class="progress-bar-wrap">
                <div class="progress-bar-fill" style="width: <?php echo $izlendi_yuzde; ?>%; background: linear-gradient(90deg, #10b981, #34d399);"></div>
            </div>
            <span class="progress-val"><?php echo $izlendi; ?></span>
        </div>

        <div class="progress-row">
            <span class="progress-label">İzlenecek</span>
            <div class="progress-bar-wrap">
                <div class="progress-bar-fill" style="width: <?php echo $izlenecek_yuzde; ?>%; background: linear-gradient(90deg, #f59e0b, #fbbf24);"></div>
            </div>
            <span class="progress-val"><?php echo $izlenecek; ?></span>
        </div>

        <div style="margin-top:20px; padding-top:16px; border-top: 1px solid rgba(26,86,251,0.1);">
            <?php if ($toplam > 0): ?>
            <div style="display:flex; gap:14px; flex-wrap:wrap;">
                <div style="text-align:center; flex:1; min-width:80px;">
                    <div style="font-size:28px; font-weight:900; color: #10b981;"><?php echo $izlendi_yuzde; ?>%</div>
                    <div style="font-size:11px; color: var(--gray-500); text-transform:uppercase; letter-spacing:0.5px; margin-top:4px;">İzlenme Oranı</div>
                </div>
                <div style="text-align:center; flex:1; min-width:80px;">
                    <div style="font-size:28px; font-weight:900; color: var(--blue-400);"><?php echo $toplam; ?></div>
                    <div style="font-size:11px; color: var(--gray-500); text-transform:uppercase; letter-spacing:0.5px; margin-top:4px;">Toplam Kayıt</div>
                </div>
                <div style="text-align:center; flex:1; min-width:80px;">
                    <div style="font-size:28px; font-weight:900; color: var(--cyan-400);"><?php echo $ortalama_puan; ?></div>
                    <div style="font-size:11px; color: var(--gray-500); text-transform:uppercase; letter-spacing:0.5px; margin-top:4px;">Ort. Puan</div>
                </div>
            </div>
            <?php else: ?>
                <p style="color:var(--gray-500); font-size:13px; text-align:center;">Henüz kayıt yok.</p>
            <?php endif; ?>
        </div>
    </div>

    <!-- Panel 2: Yapım Formatı Dağılımı -->
    <div class="stat-panel">
        <h3>🎬 Yapım Formatı Dağılımı</h3>
        <?php if (!empty($format_sayilari)): ?>
            <?php foreach ($format_sayilari as $format => $sayi): ?>
                <?php $yuzde = $toplam > 0 ? round(($sayi / $toplam) * 100) : 0; ?>
                <div class="progress-row">
                    <span class="progress-label"><?php echo guvenliCikti($format); ?></span>
                    <div class="progress-bar-wrap">
                        <div class="progress-bar-fill" style="width: <?php echo $yuzde; ?>%;"></div>
                    </div>
                    <span class="progress-val"><?php echo $sayi; ?></span>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p style="color:var(--gray-500); font-size:13px;">Kayıt bulunamadı.</p>
        <?php endif; ?>

        <div style="margin-top:20px; padding-top:16px; border-top: 1px solid rgba(26,86,251,0.1);">
            <div style="display:flex; gap:12px; flex-wrap:wrap;">
                <div style="background: rgba(26,86,251,0.08); border: 1px solid rgba(26,86,251,0.15); border-radius:8px; padding:10px 14px; flex:1; text-align:center;">
                    <div style="font-size:11px; color:var(--gray-500); text-transform:uppercase; letter-spacing:0.5px; margin-bottom:4px;">En Eski</div>
                    <div style="font-size:18px; font-weight:800; color:var(--blue-300);"><?php echo guvenliCikti((string)$en_eski); ?></div>
                </div>
                <div style="background: rgba(26,86,251,0.08); border: 1px solid rgba(26,86,251,0.15); border-radius:8px; padding:10px 14px; flex:1; text-align:center;">
                    <div style="font-size:11px; color:var(--gray-500); text-transform:uppercase; letter-spacing:0.5px; margin-bottom:4px;">En Yeni</div>
                    <div style="font-size:18px; font-weight:800; color:var(--cyan-400);"><?php echo guvenliCikti((string)$en_yeni); ?></div>
                </div>
            </div>
        </div>
    </div>

</div>

<!-- Panel 3: En Yüksek Puanlı Yapımlar -->
<div class="stat-panel" style="margin-bottom: 24px;">
    <h3>🏆 En Yüksek Puanlı Yapımlar</h3>
    <?php if (!empty($top3)): ?>
        <div class="top-list">
            <?php foreach ($top3 as $rank => $film): ?>
                <div class="top-list-item">
                    <span class="top-rank"><?php echo $rank + 1; ?>.</span>
                    <div style="flex:1;">
                        <div class="top-title"><?php echo guvenliCikti($film['baslik'] ?? ''); ?></div>
                        <div style="font-size:11px; color:var(--gray-500); margin-top:2px;">
                            <?php echo guvenliCikti($film['yonetmen'] ?? ''); ?>
                            &bull; <?php echo guvenliCikti((string)($film['yil'] ?? '')); ?>
                        </div>
                    </div>
                    <?php echo durumRozetiUret($film['durum'] ?? 'İzlenecek'); ?>
                    <span class="top-score">⭐ <?php echo guvenliCikti((string)$film['puan']); ?></span>
                </div>
            <?php endforeach; ?>
        </div>
    <?php else: ?>
        <p style="color:var(--gray-500); font-size:13px; text-align:center; padding:20px 0;">
            Puan girilmiş kayıt bulunamadı. Kayıt eklerken puan alanını doldurun.
        </p>
    <?php endif; ?>
</div>

<!-- Hızlı Erişim -->
<div style="display:flex; gap:12px; flex-wrap:wrap; justify-content:center;">
    <a href="index.php" class="btn-secondary">← Arşiv Paneli</a>
    <a href="ekle.php" class="btn-primary">➕ Yeni Kayıt Ekle</a>
</div>

<?php include 'partials/footer.php'; ?>
