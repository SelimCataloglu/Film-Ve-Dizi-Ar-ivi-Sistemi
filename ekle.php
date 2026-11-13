<?php
/**
 * FilmArşivX — Yeni Kayıt Ekleme Formu (ekle.php)
 * Öğrenci: Muhammed Selim | İnternet Programcılığı I
 */

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once 'functions.php';

// Form alanları (Sticky Form için başlangıç değerleri)
$baslik         = '';
$tur_tipi       = 'Film';
$secilen_turler = [];
$yil            = (int)date('Y');
$puan           = '';
$yonetmen       = '';
$aciklama       = '';
$durum          = '';
$hatalar        = [];

$tum_turler = [
    'Aksiyon', 'Bilim Kurgu', 'Dram', 'Komedi', 'Korku', 'Gerilim',
    'Macera', 'Animasyon', 'Romantik', 'Suç', 'Gizem', 'Fantastik',
    'Belgesel', 'Tarih', 'Savaş', 'Biyografi', 'Aile', 'Müzikal',
    'Mini Dizi', 'Süper Kahraman'
];

// Yalnızca POST isteği işlenir (Teknik Kural 5)
if (($_SERVER['REQUEST_METHOD'] ?? '') === 'POST') {
    $baslik         = trim($_POST['baslik'] ?? '');
    $tur_tipi       = trim($_POST['tur_tipi'] ?? 'Film');
    $secilen_turler = (isset($_POST['turler']) && is_array($_POST['turler'])) ? $_POST['turler'] : [];
    $yil            = trim($_POST['yil'] ?? '');
    $puan           = trim($_POST['puan'] ?? '');
    $yonetmen       = trim($_POST['yonetmen'] ?? '');
    $aciklama       = trim($_POST['aciklama'] ?? '');
    $durum          = trim($_POST['durum'] ?? '');

    // ---- Veri Doğrulama (Madde 3.4) ----
    if (empty($baslik)) {
        $hatalar[] = 'Başlık alanı boş bırakılamaz.';
    } elseif (mb_strlen($baslik) < 2) {
        $hatalar[] = 'Başlık en az 2 karakter olmalıdır.';
    }

    if (empty($secilen_turler)) {
        $hatalar[] = 'En az bir tür seçmeniz gerekmektedir.';
    }

    if (empty($aciklama)) {
        $hatalar[] = 'Açıklama alanı boş bırakılamaz.';
    } elseif (mb_strlen($aciklama) < 10) {
        $hatalar[] = 'Açıklama en az 10 karakter olmalıdır.';
    }

    if (!in_array($durum, ['İzlendi', 'İzlenecek'], true)) {
        $hatalar[] = 'Geçerli bir izlenme durumu seçmelisiniz.';
    }

    if ($yil !== '') {
        $yil_int = filter_var($yil, FILTER_VALIDATE_INT);
        if ($yil_int === false || $yil_int < 1895 || $yil_int > ((int)date('Y') + 5)) {
            $hatalar[] = 'Çıkış yılı 1895 ile ' . ((int)date('Y') + 5) . ' arasında olmalıdır.';
        } else {
            $yil = $yil_int;
        }
    } else {
        $yil = (int)date('Y');
    }

    if ($puan !== '') {
        $puan_f = filter_var($puan, FILTER_VALIDATE_FLOAT);
        if ($puan_f === false || $puan_f < 0 || $puan_f > 10) {
            $hatalar[] = 'Puan 0.0 ile 10.0 arasında olmalıdır.';
        } else {
            $puan = number_format($puan_f, 1, '.', '');
        }
    }

    // ---- Hata yoksa kaydet ----
    if (empty($hatalar)) {
        $tur_metni = implode(' / ', $secilen_turler);

        $yeni = [
            'baslik'   => $baslik,
            'tur_tipi' => $tur_tipi,
            'tur'      => $tur_metni,
            'yil'      => $yil,
            'puan'     => $puan !== '' ? $puan : '—',
            'yonetmen' => $yonetmen !== '' ? $yonetmen : 'Belirtilmedi',
            'aciklama' => $aciklama,
            'durum'    => $durum,
        ];

        if (!isset($_SESSION['filmler']) || !is_array($_SESSION['filmler'])) {
            $_SESSION['filmler'] = varsayilanFilmleriGetir();
        }

        array_unshift($_SESSION['filmler'], $yeni);

        $_SESSION['bildirim'] = [
            'tur'   => 'basari',
            'mesaj' => '"' . guvenliCikti($baslik) . '" arşive başarıyla eklendi!'
        ];

        header('Location: index.php');
        exit;
    }
}

$page_title   = 'Yeni Kayıt Ekle';
$topbar_title = '<span>Yeni</span> Kayıt Ekle';
$active_nav   = 'ekle';
$hide_search  = true;
include 'partials/header.php';
?>

<div class="form-container">

    <!-- Hata Kutusu -->
    <?php if (!empty($hatalar)): ?>
        <div class="alert alert-danger" style="margin-bottom:20px;">
            <div class="alert-icon">⚠️</div>
            <div class="alert-body">
                <strong>Lütfen aşağıdaki hataları düzeltin:</strong>
                <ul class="error-list">
                    <?php foreach ($hatalar as $h): ?>
                        <li><?php echo guvenliCikti($h); ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        </div>
    <?php endif; ?>

    <!-- Form Kartı -->
    <div class="form-card">

        <div class="form-card-top">
            <div>
                <h2>Yapım Bilgilerini Girin</h2>
                <p>Koleksiyonunuza yeni bir film veya dizi ekleyin. Zorunlu alanlar (*) işaretlidir.</p>
            </div>
            <span class="method-pill">METHOD: POST</span>
        </div>

        <!-- Ana Form (ekle.php'e POST gönderir) -->
        <form action="ekle.php" method="POST" novalidate id="add-form">
            <div class="form-body">

                <!-- Satır 1: Başlık + Format -->
                <div class="form-row-2">
                    <div class="form-group">
                        <label class="form-label req" for="baslik">Film / Dizi Adı</label>
                        <input
                            type="text"
                            id="baslik"
                            name="baslik"
                            class="form-control"
                            placeholder="Örn: Inception, Breaking Bad..."
                            value="<?php echo guvenliCikti($baslik); ?>"
                        >
                    </div>
                    <div class="form-group">
                        <label class="form-label" for="tur_tipi">Format / Yapım Tipi</label>
                        <select id="tur_tipi" name="tur_tipi" class="form-control">
                            <?php
                            $tipler = [
                                'Film'      => '🎬 Sinema Filmi',
                                'Dizi'      => '📺 Dizi',
                                'Mini Dizi' => '🎞️ Mini Dizi',
                                'Animasyon' => '🎨 Animasyon',
                                'Belgesel'  => '🌍 Belgesel',
                            ];
                            foreach ($tipler as $val => $lbl):
                            ?>
                                <option value="<?php echo guvenliCikti($val); ?>" <?php echo $tur_tipi === $val ? 'selected' : ''; ?>>
                                    <?php echo guvenliCikti($lbl); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>

                <!-- Satır 2: Yönetmen + Yıl + Puan -->
                <div class="form-row-3">
                    <div class="form-group">
                        <label class="form-label" for="yonetmen">Yönetmen / Yapımcı</label>
                        <input
                            type="text"
                            id="yonetmen"
                            name="yonetmen"
                            class="form-control"
                            placeholder="Örn: Christopher Nolan"
                            value="<?php echo guvenliCikti($yonetmen); ?>"
                        >
                    </div>
                    <div class="form-group">
                        <label class="form-label" for="yil">Çıkış Yılı</label>
                        <input
                            type="number"
                            id="yil"
                            name="yil"
                            class="form-control"
                            placeholder="2024"
                            min="1895"
                            max="2035"
                            value="<?php echo guvenliCikti((string)$yil); ?>"
                        >
                    </div>
                    <div class="form-group">
                        <label class="form-label" for="puan">IMDb / Puan (0-10)</label>
                        <input
                            type="number"
                            step="0.1"
                            min="0"
                            max="10"
                            id="puan"
                            name="puan"
                            class="form-control"
                            placeholder="8.5"
                            value="<?php echo guvenliCikti((string)$puan); ?>"
                        >
                    </div>
                </div>

                <!-- Türler (Çoklu Checkbox — Teknik Kural 6) -->
                <div class="form-group">
                    <label class="form-label req">Kategori / Türler</label>
                    <div class="checkbox-grid">
                        <?php foreach ($tum_turler as $t): ?>
                            <label class="cb-item">
                                <input
                                    type="checkbox"
                                    name="turler[]"
                                    value="<?php echo guvenliCikti($t); ?>"
                                    <?php echo in_array($t, $secilen_turler, true) ? 'checked' : ''; ?>
                                >
                                <span class="cb-label"><?php echo guvenliCikti($t); ?></span>
                            </label>
                        <?php endforeach; ?>
                    </div>
                    <span class="hint">Birden fazla tür seçebilirsiniz. Seçimler arama ve filtrelemede etiket olarak kullanılır.</span>
                </div>

                <!-- Açıklama -->
                <div class="form-group">
                    <label class="form-label req" for="aciklama">Özet / Açıklama</label>
                    <textarea
                        id="aciklama"
                        name="aciklama"
                        class="form-control"
                        rows="4"
                        placeholder="Film veya dizinin konusu, kişisel notlarınız..."
                    ><?php echo guvenliCikti($aciklama); ?></textarea>
                    <span class="hint">En az 10 karakter. Konuyu, teması veya kişisel izlenimlerinizi yazabilirsiniz.</span>
                </div>

                <!-- Durum (Radyo Buton Kartları) -->
                <div class="form-group">
                    <label class="form-label req">İzlenme Durumu</label>
                    <div class="status-selector">
                        <label class="status-opt">
                            <input type="radio" name="durum" value="İzlendi"
                                   <?php echo $durum === 'İzlendi' ? 'checked' : ''; ?>>
                            <div>
                                <span class="status-opt-title">✅ İzlendi</span>
                                <span class="status-opt-desc">Bu yapımı daha önce izledim, tamamlandı.</span>
                            </div>
                        </label>
                        <label class="status-opt">
                            <input type="radio" name="durum" value="İzlenecek"
                                   <?php echo $durum === 'İzlenecek' ? 'checked' : ''; ?>>
                            <div>
                                <span class="status-opt-title">⏳ İzlenecek</span>
                                <span class="status-opt-desc">İzleme listemde bekliyor, daha sonra izleyeceğim.</span>
                            </div>
                        </label>
                    </div>
                </div>

            </div><!-- /form-body -->

            <!-- Form Aksiyon Butonları -->
            <div class="form-actions">
                <a href="index.php" class="btn-secondary">← Listeye Geri Dön</a>
                <button type="submit" class="btn-primary">➕ Arşive Ekle</button>
            </div>

        </form><!-- /add-form -->

    </div><!-- /form-card -->
</div><!-- /form-container -->

<?php include 'partials/footer.php'; ?>
