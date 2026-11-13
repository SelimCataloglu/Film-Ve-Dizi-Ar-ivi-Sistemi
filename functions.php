<?php
/**
 * FilmArşivX — Yardımcı Fonksiyonlar
 * Öğrenci: Muhammed Selim | İnternet Programcılığı I
 */

if (!function_exists('guvenliCikti')) {
    /**
     * 1. Fonksiyon: XSS koruması için htmlspecialchars sarmalayıcısı.
     * Kullanıcı girdilerini HTML çıktısında güvenli biçimde gösterir.
     */
    function guvenliCikti($veri) {
        if ($veri === null) return '';
        return htmlspecialchars((string)$veri, ENT_QUOTES, 'UTF-8');
    }
}

if (!function_exists('durumSayisiHesapla')) {
    /**
     * 2. Fonksiyon: Belirtilen izlenme durumuna göre kayıt sayısı hesaplar.
     */
    function durumSayisiHesapla($dizi, $aranan_durum) {
        if (!is_array($dizi)) return 0;
        $sayac = 0;
        foreach ($dizi as $film) {
            if (isset($film['durum']) && $film['durum'] === $aranan_durum) {
                $sayac++;
            }
        }
        return $sayac;
    }
}

if (!function_exists('varsayilanFilmleriGetir')) {
    /**
     * 3. Fonksiyon: Sisteme ilk girişte yüklenecek 5 örnek kayıt döndürür.
     */
    function varsayilanFilmleriGetir() {
        return [
            [
                "baslik"   => "Inception",
                "tur_tipi" => "Film",
                "tur"      => "Bilim Kurgu / Gizem",
                "yil"      => 2010,
                "puan"     => 8.8,
                "yonetmen" => "Christopher Nolan",
                "aciklama" => "Rüya içinde rüya ve bilinçaltı hırsızlığı konulu, sinema tarihinin en etkileyici bilim kurgu başyapıtlarından biri.",
                "durum"    => "İzlendi"
            ],
            [
                "baslik"   => "Interstellar",
                "tur_tipi" => "Film",
                "tur"      => "Bilim Kurgu / Macera",
                "yil"      => 2014,
                "puan"     => 8.7,
                "yonetmen" => "Christopher Nolan",
                "aciklama" => "İnsanlığın yeni bir ev arayışı, solucan delikleri ve kara delik çevresinde geçen duygu dolu uzay-zaman yolculuğu.",
                "durum"    => "İzlendi"
            ],
            [
                "baslik"   => "Breaking Bad",
                "tur_tipi" => "Dizi",
                "tur"      => "Suç / Dram",
                "yil"      => 2008,
                "puan"     => 9.5,
                "yonetmen" => "Vince Gilligan",
                "aciklama" => "Sıradan bir lise kimya öğretmeninin hayatta kalmak ve ailesini korumak için girdiği karanlık suç imparatorluğu.",
                "durum"    => "İzlendi"
            ],
            [
                "baslik"   => "The Godfather",
                "tur_tipi" => "Film",
                "tur"      => "Klasik / Suç / Dram",
                "yil"      => 1972,
                "puan"     => 9.2,
                "yonetmen" => "Francis Ford Coppola",
                "aciklama" => "Corleone mafya ailesinin nesiller boyu süren güç, sadakat ve intikam eksenindeki destansı hikayesi.",
                "durum"    => "İzlenecek"
            ],
            [
                "baslik"   => "Chernobyl",
                "tur_tipi" => "Mini Dizi",
                "tur"      => "Tarih / Dram",
                "yil"      => 2019,
                "puan"     => 9.3,
                "yonetmen" => "Johan Renck",
                "aciklama" => "1986 yılında meydana gelen nükleer santral patlamasının ve gerçeği ortaya çıkarmaya çalışan kahramanların belgesel tadındaki öyküsü.",
                "durum"    => "İzlenecek"
            ],
            [
                "baslik"   => "Avengers:Endgame",
                "tur_tipi" => "Film",
                "tur"      => "Aksiyon/Macera/Bilim Kurgu/Fantastik",
                "yil"      => 2019,
                "puan"     => 8.4,
                "yonetmen" => "Anthony Russo, Joe Russo",
                "aciklama" => "Thanos'un evrenin yarısını yok etmesinin ardından hayatta kalan kahramanların her şeyi geri döndürmek için verdikleri son ve büyük savaş.",
                "durum"    => "İzlenecek"
            ],
            [
                "baslik"   => "Recep İvedik",
                "tur_tipi" => "Film",
                "tur"      => "Komedi",
                "yil"      => 2008,
                "puan"     => 4.9,
                "yonetmen" => "Togan Gökbakar",
                "aciklama" => "Sokakta bulduğu bir cüzdanı sahibine teslim etmek için İstanbul'dan Antalya'ya doğru yola çıkan Recep'in başına gelen absürt ve komik olaylar.",
                "durum"    => "İzlendi"
            ],
            [
                "baslik"   => "Çakallarla Dans",
                "tur_tipi" => "Film",
                "tur"      => "Komedi / Macera",
                "yil"      => 2010,
                "puan"     => 5.8,
                "yonetmen" => "Murat Şeker",
                "aciklama" => "Kısa yoldan köşeyi dönmek isteyen dört yakın arkadaşın, çalıştıkları şirketin parasını zimmetlerine geçirme planlarının ters gitmesiyle yaşadıkları macera.",
                "durum"    => "İzlenecek"
            ],
            [
                "baslik"   => "The Dark Knight",
                "tur_tipi" => "Film",
                "tur"      => "Aksiyon / Suç",
                "yil"      => 2008,
                "puan"     => 9.0,
                "yonetmen" => "Christopher Nolan",
                "aciklama" => "Gotham şehrini kaosa sürükleyen anarşist suç dehası Joker'e karşı Batman'in adalet ve kaos arasındaki ince çizgide verdiği psikolojik ve fiziksel mücadele.",
                "durum"    => "İzlenecek"
            ],
        ];
    }
}

if (!function_exists('durumRozetiUret')) {
    /**
     * 4. Fonksiyon: İzlenme durumuna göre HTML rozeti döndürür.
     */
    function durumRozetiUret($durum) {
        if ($durum === 'İzlendi') {
            return '<span class="badge badge-watched">✓ İzlendi</span>';
        }
        return '<span class="badge badge-pending">◷ İzlenecek</span>';
    }
}

if (!function_exists('formatTipiSimge')) {
    /**
     * 5. Fonksiyon: Yapım tipine göre simge döndürür.
     */
    function formatTipiSimge($tip) {
        $simgeler = [
            'Film'      => '🎬',
            'Dizi'      => '📺',
            'Mini Dizi' => '🎞️',
            'Animasyon' => '🎨',
            'Belgesel'  => '🌍',
        ];
        return $simgeler[$tip] ?? '🎥';
    }
}

if (!function_exists('ortalamaPuanHesapla')) {
    /**
     * 6. Fonksiyon: Arşivdeki kayıtların ortalama puanını hesaplar.
     */
    function ortalamaPuanHesapla($dizi) {
        if (!is_array($dizi) || empty($dizi)) return '—';
        $toplam = 0;
        $sayac  = 0;
        foreach ($dizi as $film) {
            if (!empty($film['puan']) && is_numeric($film['puan'])) {
                $toplam += floatval($film['puan']);
                $sayac++;
            }
        }
        return $sayac > 0 ? number_format($toplam / $sayac, 1, '.', '') : '—';
    }
}
?>
