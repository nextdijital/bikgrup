<?php
/**
 * BİKGRUP - Grup Markaları Kontrolcüsü (Genel Sayfa & Tekil Detay)
 */

declare(strict_types=1);

require_once ROOT_PATH . '/core/View.php';

class BrandController
{
    public static function getBrandsData(): array
    {
        return [
            'koylu-tarim' => [
                'slug' => 'koylu-tarim',
                'name' => 'KÖYLÜ TARIM',
                'subtitle_tr' => 'Tarımsal Ham Madde Tedariği ve Güvenilir Küresel Ticaret',
                'subtitle_en' => 'Agricultural Raw Material Supply & Trusted Global Trade',
                'image' => 'fresh_vegetables.jpg',
                'tag_tr' => 'TEDARİK & TİCARET',
                'tag_en' => 'SUPPLY & TRADE',
                'desc_tr' => 'BİK GRUP çatısı altında faaliyet gösteren Köylü Tarım; Türkiye’nin en bereketli tarım havzalarında kurduğu sözleşmeli üretim modelleri, güçlü çiftçi kooperatif bağları ve yüksek depolama kapasitesiyle tarımsal ham madde tedariğinde sektörün öncü kuruluşlarındandır.

Gıda sanayisi, yem fabrikaları ve uluslararası toptan ticaret ağları için yüksek tonajlı hububat, bakliyat ve endüstriyel tarım mahsullerini tarladan fabrikaya kesintisiz lojistikle ulaştırır. Şeffaf kalite analizleri, zamanında teslimat prensibi ve güvenilir finansal yapısıyla Köylü Tarım, tarımsal ticarette sürdürülebilir bir iş ortaklığı sunar.',
                'desc_en' => 'Operating under BİK GRUP, Köylü Tarım is a premier supplier of agricultural raw materials with strong farming networks and large-scale grain logistics. We deliver high-tonnage grains, cereals, and industrial produce with guaranteed quality testing and punctual scheduling.',
                'features_tr' => [
                    'Yüksek Tonajlı Hububat & Yağlı Tohum Tedariği',
                    'Sözleşmeli Tarım ile Garantili Mahsul Kalitesi',
                    'Modern Silo ve Havalandırmalı Depolama Tesisleri',
                    'Doğrudan Tarladan Fabrikaya Kesintisiz Lojistik'
                ],
                'features_en' => [
                    'High-tonnage Grain & Oilseed Supply',
                    'Contracted Farming with Guaranteed Crop Quality',
                    'Modern Silos & Climate-Controlled Warehousing',
                    'Field-to-Factory Seamless Logistics Chain'
                ]
            ],
            'zeliha-sultan' => [
                'slug' => 'zeliha-sultan',
                'name' => 'ZELİHA SULTAN',
                'subtitle_tr' => 'Bereketli Topraklarda Sürdürülebilir Üretim ve Yetiştiricilik',
                'subtitle_en' => 'Sustainable Production & High-Yield Farming on Fertile Lands',
                'image' => 'blog_agriculture_hero.jpg',
                'tag_tr' => 'ÜRETİM & ÇİFTLİK',
                'tag_en' => 'PRODUCTION & FARMING',
                'desc_tr' => 'Zeliha Sultan Çiftliği, Akdeniz ve Çukurova’nın binlerce yıllık alüvyon zengini topraklarında doğaya saygılı modern tarımın simgesidir. Damla sulama altyapısı, güneş enerjili tarımsal otomasyon sistemleri ve uzman ziraat mühendisleri denetiminde narenciye, sofralık meyve ve hububat üretimi gerçekleştirmektedir.

Toprağın mikrobiyal dengesini koruyan iyi tarım uygulamalarımız (Good Agricultural Practices) sayesinde sıfır kimyasal kalıntı hedefiyle yetiştirilen mahsuller, dalından koparıldığı andan itibaren doğallığını ve yüksek besin değerlerini muhafaza eder.',
                'desc_en' => 'Zeliha Sultan Farms represents modern eco-friendly farming across rich Mediterranean lands. Utilizing precision drip irrigation, solar automation, and strict Good Agricultural Practices (GAP), we cultivate prime citrus, fruits, and grains with zero chemical residues.',
                'features_tr' => [
                    'İyi Tarım Uygulamaları (GAP) Sertifikalı Araziler',
                    'Otomasyonlu Hassas Damla Sulama ve Gübreleme',
                    'Biyolojik Mücadele ile Sıfır Kimyasal Kalıntı İlkesi',
                    'Dalından Sofraya %100 İzlenebilir Üretim Takibi'
                ],
                'features_en' => [
                    'Good Agricultural Practices (GAP) Certified Lands',
                    'Automated Drip Irrigation & Soil Nutrition Monitoring',
                    'Biological Pest Control for Zero Chemical Residues',
                    '100% Traceable Harvest-to-Table Production'
                ]
            ],
            'inci-fresh' => [
                'slug' => 'inci-fresh',
                'name' => 'İNCİ FRESH',
                'subtitle_tr' => 'Uluslararası Standartlarda Soğuk Zincir Yaş Meyve ve Sebze İhracatı',
                'subtitle_en' => 'International Cold Chain Fresh Fruit & Vegetable Export',
                'image' => 'fresh_fruits.jpg',
                'tag_tr' => 'İHRACAT & SOĞUK ZİNCİR',
                'tag_en' => 'EXPORT & COLD CHAIN',
                'desc_tr' => 'İnci Fresh, Akdeniz’in en taze meyve ve sebzelerini dünyanın dört bir yanındaki sofralarla buluşturan global ihracat markamızdır. Son teknolojiyle donatılmış paketleme tesislerimizde optik kameralarla yapılan kalibrasyon, boylama ve yüzey tarama süreçleri her ürünün birinci sınıf standartta olmasını garanti eder.

Avrupa, Orta Doğu, Rusya ve Asya pazarlarına uzanan lojistik ağımız; kesintisiz nem ve sıcaklık kontrollü frigo tır filomuz sayesinde ürünlerin tazeliğini ve diriliğini koruyarak zamanında limanlara ve süpermarket raflarına ulaştırır.',
                'desc_en' => 'İnci Fresh is our premier export brand delivering Mediterranean fresh produce globally. Equipped with optical sorting and grading tech, we ensure international standards backed by end-to-end temperature-controlled reefer fleet logistics.',
                'features_tr' => [
                    'GlobalGAP, ISO 22000 ve IFS Uluslararası Akreditasyonları',
                    'Optik Kalibrasyon ve Yapay Zekalı Leke Tarama',
                    'Kesintisiz Frigo Soğuk Zincir Lojistik Yönetimi',
                    'Avrupa, Orta Doğu ve Asya da 30 u Aşkın İhracat Pazarı'
                ],
                'features_en' => [
                    'GlobalGAP, ISO 22000 & IFS International Accreditations',
                    'Optical Calibration & AI Surface Flaw Detection',
                    'Continuous Temperature-Controlled Reefer Logistics',
                    'Export Footprint Across 30+ Countries Worldwide'
                ]
            ],
            'lara-citrus' => [
                'slug' => 'lara-citrus',
                'name' => 'LARA CITRUS',
                'subtitle_tr' => 'Akdeniz Bahçelerinden Dünya Sofralarına Seçkin Narenciye',
                'subtitle_en' => 'Handpicked Mediterranean Citrus Produce for Global Tables',
                'image' => 'citrus_showcase.jpg',
                'tag_tr' => 'NARENCİYE & VİTRİN',
                'tag_en' => 'CITRUS & PACKAGING',
                'desc_tr' => 'Akdeniz güneşinin şifasını taşıyan en kaliteli portakal, limon, mandalina ve greyfurtlar Lara Citrus markası altında dünya sofralarına sunulmaktadır. Çukurova’nın seçkin bahçelerinden özenle elle toplanan narenciyelerimiz, yüksek brix şeker oranı, bol meyve suyu verimi ve parlak kabuk yapısıyla uluslararası pazarlarda yoğun talep görmektedir.

Özel tasarımlı teleskopik koliler, ahşap kasalar ve çevre dostu file ambalaj seçenekleriyle paketlenen Lara Citrus mahsulleri, perakende zincirlerinin ve toptancı hallerinin aradığı birinci sınıf kaliteyi simgeler.',
                'desc_en' => 'Lara Citrus brings sun-ripened oranges, lemons, mandarins, and grapefruits from pristine orchards. Featuring high brix sweetness and maximum juice yield, our produce is packed in premier telescopic cartons and eco-friendly packaging.',
                'features_tr' => [
                    'Yüksek Brix Tatlılık ve Maksimum Meyve Suyu Randımanı',
                    'Özenli Elle Hasat ve Çürük/Hata Eleme Protokolü',
                    'Özel Baskılı İhracat Teleskopik Karton ve File Çözümleri',
                    'Uzun Raf Ömrü Sağlayan Kontrollü İklimlendirme'
                ],
                'features_en' => [
                    'High Brix Sweetness & Maximum Juice Extraction',
                    'Gentle Handpicked Harvest & Flawless Sorting Protocols',
                    'Custom Printed Telescopic Cartons & Eco Mesh Bags',
                    'Controlled Atmosphere Storage for Extended Shelf Life'
                ]
            ],
        ];
    }

    /**
     * Genel Grup Markalarımız Sayfası
     */
    public function index(): void
    {
        $brands = self::getBrandsData();
        $lang = Lang::current();

        $pageTitle = ($lang === 'en' ? 'Our Group Brands' : 'Grup Markalarımız') . ' | ' . Settings::get('site_name', 'BİK GRUP');

        View::render('brands', [
            'pageTitle' => $pageTitle,
            'brands'    => $brands,
            'activeNav' => 'brands'
        ]);
    }

    /**
     * Tekil Grup Markası Detay Sayfası
     */
    public function detail(string $slug): void
    {
        $allBrands = self::getBrandsData();
        $slug = strtolower(trim($slug));

        if (empty($slug) || !isset($allBrands[$slug])) {
            $this->index();
            return;
        }

        $brand = $allBrands[$slug];
        $otherBrands = array_filter($allBrands, fn($b) => $b['slug'] !== $slug);

        $title = $brand['name'] . ' | ' . Settings::get('site_name', 'BİK GRUP');

        View::render('brand_detail', [
            'pageTitle'   => $title,
            'brand'       => $brand,
            'otherBrands' => $otherBrands,
            'activeNav'   => 'brands'
        ]);
    }
}
