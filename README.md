1. Projenin Adı ve Amacı

Proje Adı: FilmArşivX (Dinamik Film/Dizi & Rutin Takip Sistemi)

Projenin Amacı: Bu uygulama, kullanıcıların izledikleri ve izleyecekleri film, dizi ya da günlük rutinlerini modern ve sinematik bir arayüz üzerinden düzenli bir şekilde kayıt altına almalarını sağlar. Veritabanı karmaşasına girmeden oturum ($_SESSION) yönetimiyle dinamik veri işleme olanağı sunar. Kullanıcıya içeriklerini arama, durumlarına göre filtreleme ve tek tıkla yönetme imkânı vererek kişisel arşivleme sürecini kolaylaştırır.

2. Projede Bulunan Özellikler

Dinamik İstatistik Paneli: Toplam kayıt sayısı, tamamlanan/izlenen ve izlenecek/bekleyen içerik sayısını renkli sol vurgularla canlı olarak hesaplar ve gösterir.

Gelişmiş Anlık Arama (q Parametresi): Başlık, açıklama ve tür kategorilerinde eşzamanlı arama imkânı sunar.

Kategori ve Durum Filtreleme: Kayıtları Tümü, İzlendi ve İzlenecek durumlarına göre anında filtreler.

Kayıt Ekleme (ekle.php): Çoklu tür seçimi (checkbox) ve detaylı açıklama ile sisteme yeni film/rutin ekler.

Kayıt Silme (sil.php): İlgili dizin dizilimini (array) bozmadan kayıtları sistemden güvenli bir şekilde kaldırır.

Sinematik Koyu Tema: Gövdesi siyah (#000000), üst bilgisi antrasit (#1e293b) ve gövde arka planı açık mavi (#cce5ff) olan özgün kart yapısı.

Oturum Yönetimi ($_SESSION): Tarayıcı kapatılmadığı sürece verilerin güvenle korunmasını ve güncellenmesini sağlar.

3. Klasör Yapısı ve Dosya Açıklamaları

/rutintakipx (veya /filmarsivx)
│
├── index.php           # Ana panel, istatistikler, arama/filtreleme ve kart listesi
├── ekle.php            # Yeni film/rutin ekleme formu ve POST işlemi
├── sil.php             # GET parametresiyle gelen kaydı $_SESSION'dan silen dosya
├── functions.php       # Durum hesaplama ve guvenliCikti() güvenlik fonksiyonları
└── assets/
    └── style.css       # Tüm modern, sinematik ve siyah tema CSS kuralları


index.php: Uygulamanın ana giriş noktasıdır. Oturum başlatma, istatistik paneli, filtreleme ve arama mantığı ile kartların gösterimini barındırır.

ekle.php: Kullanıcıdan başlık, türler (çoklu seçim) ve durum bilgisini alarak $_SESSION['filmler'] dizisine ekler.

sil.php: Seçilen kaydın indeks numarasını alıp unset() ve array_values() fonksiyonları ile oturum verisini günceller.

functions.php: XSS saldırılarına karşı htmlspecialchars() ile güvenlik sağlayan guvenliCikti() ve sayısal istatistikleri hesaplayan yardımcı fonksiyonları içerir.

assets/style.css: Açık mavi gövde arka planı, antrasit banner, siyah kart gövdeleri ve renkli vurgu çizgilerini içeren özgün CSS stil dosyasıdır.

4. XAMPP Üzerinde Projeyi Çalıştırma Adımları

XAMPP'ı Başlatın: XAMPP Control Panel uygulamasını açın ve Apache modülünün yanındaki Start butonuna tıklayın.

Proje Klasörünü Taşıyın: Proje klasörünüzü (rutintakipx veya filmarsivx) aşağıdaki dizine yapıştırın:

Windows: C:\xampp\htdocs\rutintakipx

macOS: /Applications/XAMPP/htdocs/rutintakipx

Tarayıcıda Açın: Chrome veya Edge tarayıcınızı açarak adres çubuğuna şu adresi yazın ve Enter'a basın:

http://localhost/rutintakipx/index.php


5. Projede Bilinen Eksikler ve Gelecek Geliştirmeler

Kalıcı Veritabanı (MySQL): Veriler şu anda $_SESSION üzerinde tutulduğu için Apache sunucusu veya tarayıcı oturumu kapatıldığında veriler sıfırlanır. (İlerleyen aşamalarda MySQL veritabanı entegrasyonu planlanmaktadır.)

Düzenleme (Edit) Modülü: Mevcut kayıtları silmeden doğrudan güncelleme seçeneği henüz eklenmemiştir.

6. Yapay Zekâ Kullanım Açıklaması

Kullanılan Yapay Zekâ Aracı: Google Gemini

Kullanım Amaçları:

Arayüzün sınıftaki diğer projelerden farklılaşması için modern sinematik CSS renk paletlerinin (antrasit banner, siyah kart gövdeleri, açık mavi arka plan) oluşturulması.

$_SESSION dizilerindeki sıralama kaymalarını önlemek adına sil.php içindeki mantıksal hataların ayıklanması.

Çoklu tür seçimi (checkbox) ve arama fonksiyonunun (mb_stripos) entegrasyonu.

Örnek İstem (Prompt) Metni:

"PHP ile geliştirdiğim film arşiv projesinde index.php ve style.css dosyalarını güncelleyelim. Arka planı açık mavi (#cce5ff), üst banner'ı antrasit (#1e293b), film kartlarının alt kısmını ise siyah (#000000) yapalım. Ayrıca kartların sol tarafına sayı renkleriyle uyumlu renkli kenar çizgileri ekleyelim ve hem duruma göre filtreleme hem de başlığa göre arama yapabilen PHP kodlarını oluşturalım."

Kendi Geliştirmelerim ve Katkılarım:

Yapay zekadan alınan CSS şablonları projenin genel renk dengesine göre özelleştirildi.

Form verilerinin güvenlik süzgecinden geçmesi için functions.php içindeki guvenliCikti() mimarisi ve Türkçe karakter uyumlu arama mantığı (mb_stripos) kendi kurguladığım filtreleme yapısına entegre edildi.

ekle.php üzerindeki yönlendirme bağlantıları ve kullanıcı deneyimini artıran buton isimleri tarafımca düzenlendi.