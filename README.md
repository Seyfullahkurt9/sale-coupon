# Sale Coupon - WooCommerce Kupon Satış Eklentisi

Müşterilerin WooCommerce mağazasının varsayılan para biriminde istedikleri miktarda tek kullanımlık, özel hediye kuponları satın alıp kendi profillerinde (Hesabım) yönetebildikleri modüler yapıda bir WordPress eklentisi.

## Özellikler

*   **Özel Ürün Tipi (`sale_coupon`):** Satıcılar WooCommerce panelinden yeni bir "Kupon Ürünü" ekleyebilir. Fiyat dinamik olarak müşteri tarafından belirlenir.
*   **Hazır Butonlar + Serbest Giriş:** Satıcı ürün sayfasında gösterilecek hazır fiyat butonları (örn: 25₺, 50₺, 100₺) ve özel miktar giriş alanını ayarlayabilir.
*   **Kupon Satın Alımına İndirim Engeli:** Güvenlik önlemi olarak, sepetinde kupon ürünü bulunan siparişlerde hiçbir indirim kuponunun kullanılmasına izin verilmez.
*   **Tekli Satın Alma Limiti:** Sepete aynı anda yalnızca tek bir kupon ürünü eklenebilir.
*   **Güvenli ve Benzersiz Kod Üretimi:** Okunabilirliği zorlaştıran benzer karakterler (0/O, 1/I/L) elenerek, kriptografik rastgele benzersiz kupon kodları oluşturulur.
*   **Hesabım Entegrasyonu:** Müşteri satın aldığı kuponları "Hesabım > Kuponlarım" sekmesi altından görebilir, tek tıkla kopyalayabilir.
*   **E-Posta Bildirimi:** Kupon oluşturulduğunda müşteriye kupon kodunu ve ayrıntılarını içeren WooCommerce şablonuyla uyumlu e-posta gönderilir.

## Gereksinimler

*   WordPress 6.0 veya üzeri
*   WooCommerce 8.0 veya üzeri
*   PHP 7.4 veya üzeri

## Kurulum

1.  Eklenti klasörünü zip dosyası olarak indirin veya doğrudan `wp-content/plugins/` dizinine yükleyin.
2.  WordPress panelinden **Eklentiler** sayfasına gidin ve **Sale Coupon** eklentisini etkinleştirin.
3.  **Kalıcı Bağlantılar (Permalinks)** ayarlarına gidin ve değişiklikleri kaydedin (Bu işlem "Kuponlarım" sekmesinin düzgün çalışması için gereklidir).
4.  Bağımlılıkları yüklemek için eklenti dizininde aşağıdaki komutları çalıştırın (Eğer Composer ve NPM yüklü değilse zip sürümünü kullanın):

```bash
composer install --no-dev --optimize-autoloader
npm install
npm run build
```

## Yapılandırma

### Genel Ayarlar
**WooCommerce > Ayarlar > Sale Coupon** sekmesine giderek varsayılan ayarları düzenleyin:
*   Kupon kodu ön eki (Prefix)
*   Rastgele karakter uzunluğu (Güvenlik nedeniyle min: 8)
*   Minimum ve maksimum fiyat limitleri
*   Varsayılan indirim türü (Sepet/Ürün)
*   Geçerlilik süresi (gün)
*   E-posta bildirim durumu

### Ürün Bazlı Ayarlar
Yeni bir ürün eklerken **Ürün Verisi** alanından **Kupon Ürünü**'nü seçin. "Kupon Ayarları" sekmesinden genel ayarları bu ürün özelinde ezebilirsiniz.

## Lisans

Bu eklenti GPL-2.0 veya üzeri lisansıyla dağıtılmaktadır.
