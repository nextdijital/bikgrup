# BİKGRUP - Kurumsal Web Sitesi & Yönetim Paneli

Akdeniz ve Ege'nin taze mahsullerini dünya pazarlarına ulaştıran BİKGRUP için geliştirilmiş, yüksek performanslı, çok dilli (TR / EN) ve modern UI/UX mimarisine sahip kurumsal web platformu.

## 🚀 Özellikler

- **Çizgisel Mimari & Sinematik Tasarım:** Adler stilinde çerçeveler, yüksek çözünürlüklü görseller, özel mikro-animasyonlar.
- **Çok Dilli (i18n) Altyapı:** Türkçe (TR) ve İngilizce (EN) dil desteği.
- **Ürün Vitrini & Detay:** Taşan 3D kartlar, hasat sezonu, ambalaj / kutu özellikleri ve kalibre bilgileri.
- **Kesintisiz Küresel İhracat Haritası:** Dinamik rotalar ve canlı hub efektleri.
- **Gelişmiş Yönetim Paneli (`/xpanel`):** Ürün, kategori, blog, mesajlar ve site ayarları yönetimi.
- **SEO & Temiz URL:** `.htaccess` tabanlı yönlendirme ve arama motoru optimizasyonu.

## 📦 Kurulum

1. Projeyi web sunucunuza (XAMPP, Apache, Nginx) klonlayın veya taşıyın:
   ```bash
   git clone https://github.com/aydanrr/bikgrup.git
   ```
2. MySQL üzerinde `bikgrup_db` adında bir veritabanı oluşturun ve `bikgrup_db.sql` dosyasını içe aktarın:
   ```bash
   mysql -u root -p bikgrup_db < bikgrup_db.sql
   ```
3. `config/config.php` dosyasındaki veritabanı ayarlarını doğrulayın.
4. Tarayıcınızdan erişin:
   - Web Sitesi: `http://localhost/bikgrup/`
   - Yönetim Paneli: `http://localhost/bikgrup/xpanel`
