<?php
/**
 * FilmArşivX — Kayıt Silme İşlemi (sil.php)
 * Öğrenci: Muhammed Selim | İnternet Programcılığı I
 *
 * Teknik Kural 5: Silme işlemi POST yöntemiyle yapılır.
 * GET ile gelen istekler güvenlik gereği görmezden gelinir.
 */

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once 'functions.php';

$silinecek_id = null;

// POST yöntemi kontrolü (Teknik Kural 5)
if (($_SERVER['REQUEST_METHOD'] ?? '') === 'POST' && isset($_POST['id'])) {
    $silinecek_id = filter_var($_POST['id'], FILTER_VALIDATE_INT);
}

if ($silinecek_id !== null && $silinecek_id !== false
    && isset($_SESSION['filmler']) && isset($_SESSION['filmler'][$silinecek_id])) {

    $silinen_baslik = guvenliCikti($_SESSION['filmler'][$silinecek_id]['baslik'] ?? 'Seçili kayıt');

    // Kaydı sil
    unset($_SESSION['filmler'][$silinecek_id]);

    // Dizi indekslerini sıfırdan yeniden düzenle (array_values)
    $_SESSION['filmler'] = array_values($_SESSION['filmler']);

    $_SESSION['bildirim'] = [
        'tur'   => 'basari',
        'mesaj' => '"' . $silinen_baslik . '" arşivden başarıyla kaldırıldı.'
    ];

} else {
    // Geçersiz ID veya kayıt bulunamadı
    $_SESSION['bildirim'] = [
        'tur'   => 'hata',
        'mesaj' => 'Kayıt bulunamadı veya geçersiz bir ID gönderildi.'
    ];
}

header('Location: index.php');
exit;
?>
