# Sale Coupon - Coding Agent Guidelines

Bu dosya, projenin mimari bütünlüğünü korumak, sürdürülebilirliğini sağlamak ve gelecekteki geliştirmelerde kod kalitesini en üst düzeyde tutmak amacıyla kodlama yapan yapay zeka ajanlarına yönelik yönergeleri barındırır.

---

## 1. Temel Mimari Prensipler

*   **Modülerlik (PSR-4):** Projede asla monolitik kod yazılmamalıdır. Her mantıksal katman kendi PHP modülünde (`src/` altında) kapsüllenmelidir.
*   **Gereksinim Kararlılığı:** Proje doğrudan WooCommerce çekirdek sınıflarına ve WordPress API'sine bağımlıdır. Doğrudan veritabanı sorguları (`$wpdb` ile raw SQL) yazmak yerine, her zaman WooCommerce CRUD nesneleri (`WC_Coupon`, `WC_Product`, `WC_Order`) tercih edilmelidir.
*   **Context Paylaşımı (Bencillikten Kaçınma):** Kod yazan hiçbir ajan kodunu "bencilce" yazıp kapatmamalıdır. Kendinden sonra gelecek olan ajanları düşünerek kod bloklarında kararların nedenlerini açıklamalıdır (örn: *"Bunu yapmamızın sebebi X kısıtı altındaki Y durumudur"*).

---

## 2. Kodlama Kuralları ve Standartları

### A. CSS & Görsel Tasarım Kuralları
*   **Sıfır Inline CSS:** Şablon (template) dosyalarında veya PHP çıktılarında kesinlikle inline HTML `style="..."` nitelikleri kullanılmamalıdır.
*   **Stil Dağıtımı:** Tüm frontend stilleri `assets/css/frontend.css` dosyasına, tüm admin panel stilleri ise `assets/css/admin.css` dosyasına eklenmelidir.
*   **Premium Estetik:** Yeni arayüz bileşenleri eklerken modern geçiş efektleri (transitions), yumuşak gölgeler (soft shadows) ve uyumlu renk paletleri tercih edilmelidir.

### B. Dil ve Yerelleştirme (i18n)
*   **Çeviri Kancaları:** Arayüzde veya e-postalarda kullanıcıya gösterilen tüm string'ler `__()`, `_e()`, `esc_html__()` veya `wp_kses_post()` gibi WordPress i18n fonksiyonları ile sarmalanmalıdır.
*   **Text Domain:** Tüm çeviri fonksiyonlarında ikinci parametre olarak mutlaka `'sale-coupon'` text domain'i kullanılmalıdır.
*   **POT Dosyası Uyumluluğu:** Yeni metinler eklendiğinde `languages/sale-coupon.pot` dosyası taranarak güncellenmelidir.

### C. Güvenlik Standartları
*   **Çıktı Güvenliği (Escaping):** Tüm veri çıktılarında `esc_html()`, `esc_attr()`, `esc_url()` veya HTML barındıran zengin metinlerde `wp_kses_post()` kullanılmalıdır.
*   **REST API Koruma:** Tüm REST endpoint tanımlarında (`register_rest_route`) mutlaka bir `permission_callback` tanımlanmalı ve yetki doğrulaması yapılmalıdır. Session koruması için `X-WP-Nonce` header'ı zorunlu tutulmalıdır.
*   **Idempotency (Yinelenme Koruması):** Sipariş tamamlanma gibi hook'larda, aynı sipariş için yanlışlıkla birden fazla kupon kodu üretilmemesi için sipariş meta kayıtları (`_sc_generated_coupon_code` gibi) kontrol edilerek işlemler idempotent hale getirilmelidir.

---

## 3. GitHub Otomatik Güncelleyici (Plugin Updater) Entegrasyonu

Eklenti, GitHub releases üzerinden otomatik güncellenmektedir. Sürüm yayınlama aşamalarında:
1.  [sale-coupon.php](sale-coupon.php) dosyasındaki `Version:` başlığı ve `SALE_COUPON_VERSION` sabiti güncellenmelidir.
2.  [package.json](package.json) dosyasındaki versiyon alanı güncellenmelidir.
3.  `npm run build` komutu çalıştırılarak derlenmiş JS varlıkları güncellenmelidir.
4.  Git commit atılıp yeni versiyon numarasıyla tag oluşturulmalıdır (örn: `git tag v1.2.2` ve `git push origin v1.2.2`).
5.  `bsdtar` ile Linux uyumlu (düz eğik çizgi `/` içeren) bir `sale-coupon.zip` paketi oluşturulmalı ve GitHub Release varlığı olarak yüklenmelidir.

*Not: Güncellemeleri denetleyen `YahnisElsts\PluginUpdateChecker` kütüphanesi Composer bağımlılığı olarak sisteme entegredir.*
