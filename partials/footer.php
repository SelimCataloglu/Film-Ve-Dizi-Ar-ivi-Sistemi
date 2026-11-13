        </div><!-- /page-body -->

        <!-- FOOTER -->
        <footer class="site-footer">
            <strong>FilmArşivX</strong> &bull; İnternet Programcılığı I PHP Proje Ödevi<br>
            Öğrenci: <strong>Muhammed Selim</strong> &bull; Son Teslim: 8 Kasım 2026 &bull; Dinamik Aydınlık / Karanlık Mod Destekli
        </footer>

    </div><!-- /main-content -->
</div><!-- /layout-wrapper -->

<!-- Tema Değiştirme Mantığı (JavaScript) -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    var toggleBtn = document.getElementById('theme-toggle');
    if (!toggleBtn) return;

    var themeIcon = toggleBtn.querySelector('.theme-icon');
    var themeText = toggleBtn.querySelector('.theme-text');

    function updateBtnVisual(theme) {
        if (theme === 'dark') {
            if (themeIcon) themeIcon.textContent = '☀️';
            if (themeText) themeText.textContent = 'Aydınlık Mod';
            toggleBtn.setAttribute('title', 'Aydınlık Moda Geç (Beyaz ve Açık Mavi)');
        } else {
            if (themeIcon) themeIcon.textContent = '🌙';
            if (themeText) themeText.textContent = 'Karanlık Mod';
            toggleBtn.setAttribute('title', 'Karanlık Moda Geç (Derin Lacivert ve Elektrik Mavi)');
        }
    }

    // Mevcut aktif temayı tespit et ve butonu güncelle
    var suankiTema = document.documentElement.getAttribute('data-theme') || 'light';
    updateBtnVisual(suankiTema);

    // Tıklanınca temayı değiştir
    toggleBtn.addEventListener('click', function() {
        var aktif = document.documentElement.getAttribute('data-theme') || 'light';
        var yeniTema = (aktif === 'dark') ? 'light' : 'dark';
        
        document.documentElement.setAttribute('data-theme', yeniTema);
        localStorage.setItem('filmarxiv_theme', yeniTema);
        updateBtnVisual(yeniTema);
    });
});
</script>
</body>
</html>
