# Sale Coupon - WooCommerce Kupon Satış Eklentisi

🌐 Diğer dillerde oku: [English](README.md) | [Türkçe](README.tr.md)

Müşterilerin WooCommerce mağazasının varsayılan para biriminde istedikleri miktarda tek kullanımlık, özel hediye kuponları satın alıp kendi profillerinde (Hesabım) yönetebildikleri modüler yapıda bir WordPress eklentisi.

## Özellikler

*   **Özel Ürün Tipi (`sale_coupon`):** Satıcılar WooCommerce panelinden yeni bir "Kupon Ürünü" ekleyebilir. Fiyat dinamik olarak müşteri tarafından belirlenir.
*   **Hazır Butonlar + Serbest Giriş:** Satıcı ürün sayfasında gösterilecek hazır fiyat butonları (örn: 25₺, 50₺, 100₺) ve özel miktar giriş alanını ayarlayabilir.
*   **Kupon Satın Alımına İndirim Engeli:** Güvenlik önlemi olarak, sepetinde kupon ürünü bulunan siparişlerde hiçbir indirim kuponunun kullanılmasına izin verilmez.
*   **Dinamik Fiyat Gösterimi:** Mağaza/kategori sayfalarında `0 TL` yerine, ürünün minimum ve maksimum fiyat aralığını (örn: `10 TL - 1.000 TL`) otomatik gösterir.
*   **Tekli Satın Alma Limiti:** Sepete aynı anda yalnızca tek bir kupon ürünü eklenebilir.
*   **Güvenli ve Benzersiz Kod Üretimi:** Okunabilirliği zorlaştıran benzer karakterler (0/O, 1/I/L) elenerek, kriptografik rastgele benzersiz kupon kodları oluşturulur.
*   **Hesabım Entegrasyonu:** Müşteri satın aldığı kuponları "Hesabım > Kuponlarım" sekmesi altından görebilir, tek tıkla kopyalayabilir.
*   **E-Posta Bildirimi:** Kupon oluşturulduğunda müşteriye kupon kodunu ve ayrıntılarını içeren WooCommerce şablonuyla uyumlu e-posta gönderilir.

## Gereksinimler

*   WordPress 6.0 veya üzeri
*   WooCommerce 8.0 veya üzeri
*   PHP 7.4 veya üzeri

## Kurulum

### 1. Hazır Paket ile Kolay Kurulum (Önerilen)
Eklentiyi kullanmak için en kolay yol, derlenmiş ve tüm bağımlılıkları barındıran hazır paket sürümünü yüklemektir:
1. GitHub Releases sayfasından en güncel **`sale-coupon.zip`** arşivini indirin (GitHub'ın otomatik oluşturduğu "Source code" zip dosyalarını değil, harici varlık olarak yüklenen `sale-coupon.zip` dosyasını indirmelisiniz).
2. WordPress yönetim panelinizden **Eklentiler > Yeni Ekle > Eklenti Yükle** adımlarını takip ederek zip dosyasını yükleyin ve etkinleştirin.
3. Bu hazır paket, tüm PHP bağımlılıklarını (`vendor` klasörü) ve derlenmiş frontend varlıklarını içerdiğinden sunucunuzda Composer veya NPM çalıştırmanıza gerek kalmaz.

### 2. Geliştiriciler İçin Kaynak Koddan Kurulum
Eklenti üzerinde geliştirme yapmak veya kaynak koddan derlemek istiyorsanız:
1. Depoyu klonlayın veya doğrudan `wp-content/plugins/sale-coupon` klasörüne indirin.
2. Eklenti dizininde bağımlılıkları kurmak ve varlıkları derlemek için sırasıyla şu komutları çalıştırın:
   ```bash
   composer install --no-dev --optimize-autoloader
   npm install
   npm run build
   ```
3. WordPress panelinden **Sale Coupon** eklentisini etkinleştirin.

### Kurulum Sonrası Önemli Adım
Eklenti etkinleştirildikten sonra, WordPress yönetim panelinde **Ayarlar > Kalıcı Bağlantılar (Permalinks)** sayfasına gidip hiçbir değişiklik yapmadan **Değişiklikleri Kaydet** butonuna tıklayın. Bu işlem, "Hesabım" sayfasındaki "Kuponlarım" sekmesinin düzgün çalışabilmesi için WordPress yönlendirme kurallarını sıfırlamak üzere zorunludur.

## Yapılandırma

### Genel Ayarlar
**WooCommerce > Ayarlar > Sale Coupon** sekmesine giderek varsayılan ayarları düzenleyin:
*   Kupon kodu ön eki (Prefix)
*   Rastgele karakter uzunluğu (Güvenlik nedeniyle min: 8)
*   Minimum ve maksimum fiyat limitleri
*   Varsayılan indirim türü (Sepet/Ürün İndirimi)
*   E-posta bildirim durumu

### Ürün Bazlı Ayarlar
Yeni bir ürün eklerken **Ürün Verisi** alanından **Kupon Ürünü**'nü seçin. "Kupon Ayarları" sekmesinden genel ayarları bu ürün özelinde ezebilirsiniz.

## Lisans ve Çifte Lisanslama (Dual Licensing)

Bu eklenti **GNU AGPLv3 (Affero General Public License v3)** ve **Ticari Lisans** olmak üzere çifte lisanslama modeliyle dağıtılmaktadır.

*   **Açık Kaynak Kullanım (AGPLv3):** Eklentiyi açık kaynaklı projelerinizde veya kendi sitelerinizde ücretsiz olarak kullanabilirsiniz. Ancak, eklenti kodunu değiştirip bir web sitesinde/hizmette kullanırsanız, yaptığınız değişiklikleri AGPLv3 lisansı altında açık kaynak olarak paylaşmak **zorundasınız**. Kodları kapalı kaynak haline getirip veya olduğu gibi satamazsınız.
*   **Ticari Kullanım Lisansı:** Eğer eklenti kodlarını kapalı kaynaklı (proprietary) bir projeye dahil etmek, kodları müşterilerinize kaynak kodunu açmadan satmak veya AGPLv3 kısıtlamalarından muaf olmak istiyorsanız, yazar ile iletişime geçerek ticari lisans satın almanız gerekmektedir.

Detaylar için [LICENSE](file:///c:/Users/fikri/Desktop/avdini.com/Sale_Coupon/LICENSE) dosyasını inceleyebilirsiniz.
