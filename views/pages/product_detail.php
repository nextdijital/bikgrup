<?php
/**
 * BİKGRUP - Ürün Detay Sayfası
 * Görsel Referanslarına Birebir Uygun:
 * 1. Hero Showcase (Dairesel Backdrop, Besin Değerleri, Contents & Teklif Butonu)
 * 2. Meyve Karakteristikleri (Et Rengi, Çekirdek, Kabuk Rengi, Doku, Şekil, Tat)
 * 3. Ürün Cinsleri & Çeşitleri
 * 4. 12 Aylık Hasat & İhracat Sezon Takvimi
 * 5. İhracat Ambalaj & Kutu Seçenekleri (7kg, 15kg, Teleskopik, File)
 */

declare(strict_types=1);

$currentLang = Lang::current();
$prodName = $product['name_' . $currentLang] ?? $product['name_tr'];
$prodShort = $product['short_desc_' . $currentLang] ?? $product['short_desc_tr'];
$prodContent = $product['content_' . $currentLang] ?? $product['content_tr'];
$catName = $product['category_name_' . $currentLang] ?? $product['category_name_tr'];
$whatsapp = Settings::get('site_whatsapp', '+905320000000');
$slug = $product['slug'];

// Dinamik Hasat Sezonu Ay Çözümleyici (Veritabanındaki metne göre ayları otomatik belirler)
if (!function_exists('resolveHarvestMonths')) {
    function resolveHarvestMonths(string $text): array {
        $text = mb_strtolower($text, 'UTF-8');
        $all = ['AĞU', 'EYL', 'EKİ', 'KAS', 'ARA', 'OCA', 'ŞUB', 'MAR', 'NİS', 'MAY', 'HAZ', 'TEM'];
        if (str_contains($text, 'tüm yıl') || str_contains($text, 'tum yil') || str_contains($text, 'dört mevsim') || str_contains($text, 'dort mevsim') || str_contains($text, 'all year')) {
            return $all;
        }
        
        $map = [
            'ocak' => 'OCA', 'şubat' => 'ŞUB', 'subat' => 'ŞUB', 'mart' => 'MAR', 'nisan' => 'NİS',
            'mayıs' => 'MAY', 'mayis' => 'MAY', 'haziran' => 'HAZ', 'temmuz' => 'TEM', 'ağustos' => 'AĞU',
            'agustos' => 'AĞU', 'eylül' => 'EYL', 'eylul' => 'EYL', 'ekim' => 'EKİ', 'kasım' => 'KAS',
            'kasim' => 'KAS', 'aralık' => 'ARA', 'aralik' => 'ARA'
        ];
        $cal = ['OCA', 'ŞUB', 'MAR', 'NİS', 'MAY', 'HAZ', 'TEM', 'AĞU', 'EYL', 'EKİ', 'KAS', 'ARA'];
        
        $positions = [];
        foreach ($map as $name => $abbr) {
            $pos = mb_strpos($text, $name);
            if ($pos !== false) {
                $positions[$pos] = $abbr;
            }
        }
        ksort($positions);
        $found = array_values($positions);

        if (empty($found)) return ['MAY', 'HAZ', 'TEM', 'AĞU'];
        if (count($found) === 1) return $found;
        
        $s = array_search($found[0], $cal, true);
        $e = array_search($found[count($found)-1], $cal, true);
        if ($s !== false && $e !== false) {
            $res = [];
            $i = $s;
            while (true) {
                $res[] = $cal[$i];
                if ($i === $e) break;
                $i = ($i + 1) % 12;
                if (count($res) > 12) break;
            }
            return $res;
        }
        return $found;
    }
}

// Ürün ikonunu meyve/sebze türüne göre dinamik belirleme
$lowerName = mb_strtolower($prodName . ' ' . $slug . ' ' . $catName, 'UTF-8');
if (str_contains($lowerName, 'karpuz')) {
    $prodIcon = '🍉';
} elseif (str_contains($lowerName, 'limon')) {
    $prodIcon = '🍋';
} elseif (str_contains($lowerName, 'greyfurt') || str_contains($lowerName, 'ruby')) {
    $prodIcon = '🍊';
} elseif (str_contains($lowerName, 'portakal')) {
    $prodIcon = '🍊';
} elseif (str_contains($lowerName, 'mandalina') || str_contains($lowerName, 'klementin')) {
    $prodIcon = '🍊';
} elseif (str_contains($lowerName, 'domates')) {
    $prodIcon = '🍅';
} elseif (str_contains($lowerName, 'çilek') || str_contains($lowerName, 'cilek')) {
    $prodIcon = '🍓';
} elseif (str_contains($lowerName, 'üzüm') || str_contains($lowerName, 'uzum')) {
    $prodIcon = '🍇';
} elseif (str_contains($lowerName, 'elma')) {
    $prodIcon = '🍎';
} elseif (str_contains($lowerName, 'nar')) {
    $prodIcon = '🍎';
} elseif (str_contains($lowerName, 'sebze') || str_contains($lowerName, 'biber')) {
    $prodIcon = '🥦';
} else {
    $prodIcon = '🌿';
}

// Ürüne özel cinsler, hasat takvimi, besin değerleri ve karakteristik verileri
$varietiesData = [
    'star-ruby-greyfurt' => [
        [
            'name' => 'Star Ruby',
            'desc' => 'Koyu kırmızı iç et, ince kabuk ve zengin tatlı narenciye lezzeti.',
            'months' => ['EKİ', 'KAS', 'ARA', 'OCA', 'ŞUB', 'MAR', 'NİS', 'MAY'],
            'nutrition' => ['kcal' => '42', 'fat' => '0.1 g', 'carb' => '10.7 g', 'protein' => '0.8 g'],
            'char' => [
                'cekirdek' => '1 - 2 Çekirdek',
                'kabuk'    => 'Pembemsi Turuncu, Kırmızı Yanaklı (İnce)',
                'et'       => 'Koyu Kırmızı',
                'tat'      => 'Tatlı ve Aromatik',
                'doku'     => 'Yumuşak Bol Sulu',
                'sekil'    => 'Yuvarlak, Hafif Basık'
            ]
        ],
        [
            'name' => 'Ruby Red',
            'desc' => 'Açık pembe iç et, parlak sarı kırmızı yanaklı kabuk ve diri sululuk.',
            'months' => ['EKİ', 'KAS', 'ARA', 'OCA', 'ŞUB', 'MAR', 'NİS', 'MAY'],
            'nutrition' => ['kcal' => '41', 'fat' => '0.1 g', 'carb' => '10.5 g', 'protein' => '0.8 g'],
            'char' => [
                'cekirdek' => '1 - 2 Çekirdek',
                'kabuk'    => 'Parlak Sarı, Kırmızı Yanaklı (İnce)',
                'et'       => 'Açık Pembe',
                'tat'      => 'Tatlı ve Ferahlatıcı Dengeli Asit',
                'doku'     => 'Diri, Bol Sulu',
                'sekil'    => 'Yuvarlak, Hafif Basık'
            ]
        ],
        [
            'name' => 'Rio Red',
            'desc' => 'Kırmızımsı turuncu kabuk, koyu kırmızı et ve yüksek su oranı.',
            'months' => ['EKİ', 'KAS', 'ARA', 'OCA', 'ŞUB', 'MAR', 'NİS', 'MAY'],
            'nutrition' => ['kcal' => '44', 'fat' => '0.1 g', 'carb' => '11.2 g', 'protein' => '0.9 g'],
            'char' => [
                'cekirdek' => '1 - 2 Çekirdek',
                'kabuk'    => 'Kırmızımsı - Turuncu Kırmızı Yanaklı (İnce)',
                'et'       => 'Koyu Kırmızı',
                'tat'      => 'Tatlı ve Lezzetli',
                'doku'     => 'Su Oranı Yüksek, Tatlı ve Lezzetli',
                'sekil'    => 'Yuvarlak, Hafif Basık'
            ]
        ],
        [
            'name' => 'White Marsh Seedless',
            'desc' => 'Pürüzsüz parlak açık sarı kabuk, mat sarı çekirdeksiz et ve ferahlatıcı lezzet.',
            'months' => ['EKİ', 'KAS', 'ARA', 'OCA', 'ŞUB', 'MAR', 'NİS', 'MAY'],
            'nutrition' => ['kcal' => '39', 'fat' => '0.1 g', 'carb' => '9.8 g', 'protein' => '0.7 g'],
            'char' => [
                'cekirdek' => 'Çekirdeksiz',
                'kabuk'    => 'Pürüzsüz, Parlak Açık Sarı (İnce)',
                'et'       => 'Mat Sarı',
                'tat'      => 'Ferahlatıcı Ekşi & Aromatik',
                'doku'     => 'Diri, Bol Sulu',
                'sekil'    => 'Yuvarlak, Hafif Basık'
            ]
        ],
    ],
    'white-marsh-seedless' => [
        [
            'name' => 'White Marsh Seedless',
            'desc' => 'Pürüzsüz parlak açık sarı kabuk, mat sarı çekirdeksiz et ve ferahlatıcı lezzet.',
            'months' => ['EKİ', 'KAS', 'ARA', 'OCA', 'ŞUB', 'MAR', 'NİS', 'MAY'],
            'nutrition' => ['kcal' => '39', 'fat' => '0.1 g', 'carb' => '9.8 g', 'protein' => '0.7 g'],
            'char' => [
                'cekirdek' => 'Çekirdeksiz',
                'kabuk'    => 'Pürüzsüz, Parlak Açık Sarı (İnce)',
                'et'       => 'Mat Sarı',
                'tat'      => 'Ferahlatıcı Ekşi & Aromatik',
                'doku'     => 'Diri, Bol Sulu',
                'sekil'    => 'Yuvarlak, Hafif Basık'
            ]
        ],
        [
            'name' => 'Star Ruby',
            'desc' => 'Koyu kırmızı iç et, ince kabuk ve zengin tatlı narenciye lezzeti.',
            'months' => ['EKİ', 'KAS', 'ARA', 'OCA', 'ŞUB', 'MAR', 'NİS', 'MAY'],
            'nutrition' => ['kcal' => '42', 'fat' => '0.1 g', 'carb' => '10.7 g', 'protein' => '0.8 g'],
            'char' => [
                'cekirdek' => '1 - 2 Çekirdek',
                'kabuk'    => 'Pembemsi Turuncu, Kırmızı Yanaklı (İnce)',
                'et'       => 'Koyu Kırmızı',
                'tat'      => 'Tatlı ve Aromatik',
                'doku'     => 'Yumuşak Bol Sulu',
                'sekil'    => 'Yuvarlak, Hafif Basık'
            ]
        ],
        [
            'name' => 'Rio Red',
            'desc' => 'Kırmızımsı turuncu kabuk, koyu kırmızı et ve yüksek su oranı.',
            'months' => ['EKİ', 'KAS', 'ARA', 'OCA', 'ŞUB', 'MAR', 'NİS', 'MAY'],
            'nutrition' => ['kcal' => '44', 'fat' => '0.1 g', 'carb' => '11.2 g', 'protein' => '0.9 g'],
            'char' => [
                'cekirdek' => '1 - 2 Çekirdek',
                'kabuk'    => 'Kırmızımsı - Turuncu Kırmızı Yanaklı (İnce)',
                'et'       => 'Koyu Kırmızı',
                'tat'      => 'Tatlı ve Lezzetli',
                'doku'     => 'Su Oranı Yüksek, Tatlı ve Lezzetli',
                'sekil'    => 'Yuvarlak, Hafif Basık'
            ]
        ],
        [
            'name' => 'Ruby Red',
            'desc' => 'Açık pembe iç et, parlak sarı kırmızı yanaklı kabuk ve diri sululuk.',
            'months' => ['EKİ', 'KAS', 'ARA', 'OCA', 'ŞUB', 'MAR', 'NİS', 'MAY'],
            'nutrition' => ['kcal' => '41', 'fat' => '0.1 g', 'carb' => '10.5 g', 'protein' => '0.8 g'],
            'char' => [
                'cekirdek' => '1 - 2 Çekirdek',
                'kabuk'    => 'Parlak Sarı, Kırmızı Yanaklı (İnce)',
                'et'       => 'Açık Pembe',
                'tat'      => 'Tatlı ve Ferahlatıcı Dengeli Asit',
                'doku'     => 'Diri, Bol Sulu',
                'sekil'    => 'Yuvarlak, Hafif Basık'
            ]
        ],
    ],
    'washington-portakal' => [
        [
            'name' => 'Washıngton Navel',
            'desc' => 'Göbekli, sofralık tüketimin dünya lideri.',
            'months' => ['ARA', 'OCA', 'ŞUB'],
            'nutrition' => ['kcal' => '47', 'fat' => '0.1 g', 'carb' => '11.8 g', 'protein' => '0.9 g'],
            'char' => [
                'cekirdek' => '1 - 2 Çekirdek',
                'kabuk'    => 'Koyu Turuncu',
                'et'       => 'Turuncu',
                'tat'      => 'Tatlı, Aromatik',
                'doku'     => 'Gevrek, Bol Sulu',
                'sekil'    => 'Yuvarlağa Yakın'
            ]
        ],
        [
            'name' => 'Valencia',
            'desc' => 'Geç hasat avantajı, pürüzsüz kabuk ve zengin lezzet.',
            'months' => ['ŞUB', 'MAR', 'NİS', 'MAY', 'HAZ'],
            'nutrition' => ['kcal' => '48', 'fat' => '0.2 g', 'carb' => '12.0 g', 'protein' => '0.9 g'],
            'char' => [
                'cekirdek' => 'Çekirdeksiz (Nadiren 1- 2)',
                'kabuk'    => 'Koyu Turuncu',
                'et'       => 'Turuncu',
                'tat'      => 'Tatlı, Aromatik',
                'doku'     => 'Gevrek, Bol Sulu',
                'sekil'    => 'Oval'
            ]
        ],
        [
            'name' => 'Spring Navel',
            'desc' => 'Parlak renk, dengeli şeker oranı ve bol meyve suyu.',
            'months' => ['OCA', 'ŞUB', 'MAR'],
            'nutrition' => ['kcal' => '46', 'fat' => '0.1 g', 'carb' => '11.5 g', 'protein' => '0.9 g'],
            'char' => [
                'cekirdek' => 'Çekirdeksiz',
                'kabuk'    => 'Parlak Turuncu',
                'et'       => 'Turuncu',
                'tat'      => 'Tatlı',
                'doku'     => 'Bol Sulu',
                'sekil'    => 'Yuvarlak'
            ]
        ],
        [
            'name' => 'Fukumoto',
            'desc' => 'Erkenci göbekli portakal, koyu kırmızımsı kabuk ve leziz tat.',
            'months' => ['EKİ', 'KAS', 'ARA'],
            'nutrition' => ['kcal' => '47', 'fat' => '0.1 g', 'carb' => '11.6 g', 'protein' => '0.9 g'],
            'char' => [
                'cekirdek' => 'Çekirdeksiz',
                'kabuk'    => 'Koyu Kırmızımsı',
                'et'       => 'Turuncu',
                'tat'      => 'Tatlı',
                'doku'     => 'Göbekli ve Sulu',
                'sekil'    => 'Düzgün Yuvarlak Şekilli'
            ]
        ],
        [
            'name' => 'Cara Cara',
            'desc' => 'Pembemsi turuncu iç et, düşük asit ve yüksek likopen.',
            'months' => ['KAS', 'ARA', 'OCA'],
            'nutrition' => ['kcal' => '50', 'fat' => '0.2 g', 'carb' => '12.5 g', 'protein' => '1.0 g'],
            'char' => [
                'cekirdek' => 'Çekirdeksiz',
                'kabuk'    => 'Turuncu',
                'et'       => 'Pembemsi Turuncu',
                'tat'      => 'Tatlı',
                'doku'     => 'Gevrek ve Su Oranı Yüksek',
                'sekil'    => 'Düzgün Yuvarlak Şekilli'
            ]
        ],
    ],
    'mandalina' => [
        [
            'name' => 'Tango',
            'desc' => 'Tamamen çekirdeksiz, yassı yuvarlak ve zengin tatlı lezzet.',
            'months' => ['ARA', 'OCA', 'ŞUB', 'MAR'],
            'nutrition' => ['kcal' => '53', 'fat' => '0.3 g', 'carb' => '13.3 g', 'protein' => '0.8 g'],
            'char' => [
                'cekirdek' => 'Çekirdeksiz',
                'kabuk'    => 'Turuncu, Sarı',
                'et'       => 'Koyu Turuncu',
                'tat'      => 'Tatlı, Çok Lezzetli',
                'doku'     => 'Bol Sulu, Etli',
                'sekil'    => 'Yassı Yuvarlak'
            ]
        ],
        [
            'name' => 'Primasol',
            'desc' => 'Erkenci mandalina çeşidi, hafif pürüzlü kabuk ve sulu yapı.',
            'months' => ['EYL', 'EKİ'],
            'nutrition' => ['kcal' => '50', 'fat' => '0.2 g', 'carb' => '12.8 g', 'protein' => '0.7 g'],
            'char' => [
                'cekirdek' => 'Çekirdeksiz',
                'kabuk'    => 'Turuncu, Hafif Pürüzlü',
                'et'       => 'Koyu Turuncu',
                'tat'      => 'Tatlı',
                'doku'     => 'Sulu',
                'sekil'    => 'Yuvarlak, Hafif Yassı'
            ]
        ],
        [
            'name' => 'Nova',
            'desc' => 'Yoğun aromatik tat, ince kırmızımsı turuncu kabuk ve sulu doku.',
            'months' => ['KAS', 'ARA', 'OCA', 'ŞUB'],
            'nutrition' => ['kcal' => '52', 'fat' => '0.2 g', 'carb' => '13.0 g', 'protein' => '0.8 g'],
            'char' => [
                'cekirdek' => '2 - 3 Çekirdek',
                'kabuk'    => 'İnce Kırmızımsı, Turuncu',
                'et'       => 'Koyu Turuncu',
                'tat'      => 'Aromatik Tatlı ve Lezzetli',
                'doku'     => 'İnce Dokulu ve Sulu',
                'sekil'    => 'Yarı Küresel'
            ]
        ],
        [
            'name' => 'Dobashibeni',
            'desc' => 'Koyu turuncu renk, etli dilimleme kalitesi ve çekirdeksiz yapı.',
            'months' => ['EKİ', 'KAS', 'ARA'],
            'nutrition' => ['kcal' => '49', 'fat' => '0.2 g', 'carb' => '12.4 g', 'protein' => '0.8 g'],
            'char' => [
                'cekirdek' => 'Çekirdeksiz',
                'kabuk'    => 'Koyu Turuncu',
                'et'       => 'Turuncu',
                'tat'      => 'Tatlı',
                'doku'     => 'Bol Sulu, Etli',
                'sekil'    => 'Yassı Yuvarlak'
            ]
        ],
        [
            'name' => 'Satsuma',
            'desc' => 'Çok kolay soyulan sarı-turuncu kabuk, çekirdeksiz ve geleneksel aromatik lezzet.',
            'months' => ['EKİ', 'KAS', 'ARA'],
            'nutrition' => ['kcal' => '47', 'fat' => '0.2 g', 'carb' => '11.8 g', 'protein' => '0.8 g'],
            'char' => [
                'cekirdek' => 'Çekirdeksiz',
                'kabuk'    => 'Çok Kolay Soyulur, Sarı-Turuncu',
                'et'       => 'Koyu Turuncu',
                'tat'      => 'Aromatik ve Tatlı',
                'doku'     => 'Çok Sulu',
                'sekil'    => 'Yassı ve Yuvarlak'
            ]
        ],
        [
            'name' => 'Mihowase',
            'desc' => 'Erkenci çeşit, koyu turuncu gevrek kolay soyulan kabuk ve bol su.',
            'months' => ['EYL', 'EKİ'],
            'nutrition' => ['kcal' => '48', 'fat' => '0.2 g', 'carb' => '12.0 g', 'protein' => '0.8 g'],
            'char' => [
                'cekirdek' => 'Çekirdeksiz',
                'kabuk'    => 'Koyu Turuncu, Gevrek, Kolay Soyulur',
                'et'       => 'Koyu Turuncu',
                'tat'      => 'Tatlı',
                'doku'     => 'Sulu',
                'sekil'    => 'Yassı Yuvarlak'
            ]
        ],
        [
            'name' => 'W. Murcott',
            'desc' => 'Pürüzsüz koyu kabuk, diri et yapısı ve eşsiz yüksek aromalı tat.',
            'months' => ['ARA', 'OCA', 'ŞUB', 'MAR'],
            'nutrition' => ['kcal' => '54', 'fat' => '0.3 g', 'carb' => '13.5 g', 'protein' => '0.9 g'],
            'char' => [
                'cekirdek' => 'Yok veya Az Çekirdekli',
                'kabuk'    => 'Pürüzsüz Koyu Turuncu',
                'et'       => 'Koyu Turuncu',
                'tat'      => 'Tatlı ve Eşsiz Aroma',
                'doku'     => 'Diri ve Sulu',
                'sekil'    => 'Basık Yuvarlak'
            ]
        ],
        [
            'name' => 'Belabela',
            'desc' => 'Kırmızımsı koyu turuncu kabuk, çekirdeksiz ve bol sulu.',
            'months' => ['OCA', 'ŞUB', 'MAR'],
            'nutrition' => ['kcal' => '51', 'fat' => '0.2 g', 'carb' => '12.7 g', 'protein' => '0.8 g'],
            'char' => [
                'cekirdek' => 'Çekirdeksiz',
                'kabuk'    => 'Kırmızımsı Koyu Turuncu',
                'et'       => 'Turuncu',
                'tat'      => 'Tatlı Aromatik',
                'doku'     => 'Bol Sulu',
                'sekil'    => 'Yassı Yuvarlak'
            ]
        ],
        [
            'name' => 'Robinson',
            'desc' => 'Koyu turuncu hafif kırmızımsı renk, bol sulu ve tatlı lezzet.',
            'months' => ['KAS', 'ARA', 'OCA'],
            'nutrition' => ['kcal' => '52', 'fat' => '0.2 g', 'carb' => '12.9 g', 'protein' => '0.8 g'],
            'char' => [
                'cekirdek' => '0 - 7 Çekirdek',
                'kabuk'    => 'Koyu Turuncu Hafif Kırmızımsı',
                'et'       => 'Koyu Sarı',
                'tat'      => 'Tatlı',
                'doku'     => 'Sulu',
                'sekil'    => 'Hafif Basık'
            ]
        ],
        [
            'name' => 'Okitsu',
            'desc' => 'Erkenci hasat kalitesi, bol sulu ve sofralık tatlı lezzet.',
            'months' => ['EYL', 'EKİ', 'KAS'],
            'nutrition' => ['kcal' => '48', 'fat' => '0.2 g', 'carb' => '11.9 g', 'protein' => '0.8 g'],
            'char' => [
                'cekirdek' => 'Çekirdekli',
                'kabuk'    => 'Hafif Kırmızımsı, Koyu Turuncu',
                'et'       => 'Sarı',
                'tat'      => 'Tatlı, ve Lezzetli',
                'doku'     => 'Bol Sulu',
                'sekil'    => 'Hafif Basık'
            ]
        ],
        [
            'name' => 'Clementine',
            'desc' => 'İnce kırmızımsı turuncu kabuk, aromatik tatlı ve yarı küresel - boyunlu form.',
            'months' => ['KAS', 'ARA'],
            'nutrition' => ['kcal' => '50', 'fat' => '0.2 g', 'carb' => '12.5 g', 'protein' => '0.8 g'],
            'char' => [
                'cekirdek' => '2 - 3 Çekirdek',
                'kabuk'    => 'İnce Kırmızımsı, Turuncu',
                'et'       => 'Koyu Turuncu',
                'tat'      => 'Aromatik Tatlı ve Lezzetli',
                'doku'     => 'İnce Dokulu ve Sulu',
                'sekil'    => 'Yarı Küresel - Boyunlu'
            ]
        ],
    ],
    'mayer-limon' => [
        [
            'name' => 'Interdonat',
            'desc' => 'Erkenci ihracat limonunun lideri, silindirik göğüslü yapı ve bol sulu et.',
            'months' => ['EYL', 'EKİ', 'KAS', 'ARA'],
            'nutrition' => ['kcal' => '29', 'fat' => '0.2 g', 'carb' => '9.2 g', 'protein' => '1.0 g'],
            'char' => [
                'cekirdek' => 'Az Çekirdekli',
                'kabuk'    => 'Koyu Sarı (Orta - İnce)',
                'et'       => 'Yeşilimsi Sarı',
                'tat'      => 'Canlandırıcı Asidik / Ekşi',
                'doku'     => 'Bol Sulu',
                'sekil'    => 'Silindirik, Sap Tarafından Kısa Boyunlu, Uç Tarafı Sivri'
            ]
        ],
        [
            'name' => 'Meyer',
            'desc' => 'İnce pürüzsüz parlak kabuk, yumuşak bol sulu ve narin hafif tatlımsı asit.',
            'months' => ['AĞU', 'EYL', 'EKİ', 'KAS'],
            'nutrition' => ['kcal' => '29', 'fat' => '0.3 g', 'carb' => '9.3 g', 'protein' => '1.1 g'],
            'char' => [
                'cekirdek' => 'Az Çekirdekli',
                'kabuk'    => 'Parlak Sarı, Pürüzsüz (İnce)',
                'et'       => 'Soluk Turuncu Sarı',
                'tat'      => 'Normalden Tatlı (Hafif Asidik)',
                'doku'     => 'Yumuşak Bol Sulu',
                'sekil'    => 'Oval Şekilli'
            ]
        ],
        [
            'name' => 'Eureka',
            'desc' => 'Canlı sarı pürüzlü kabuk, sivri uçlu oval meyve ve zengin aromatik ekşi lezzet.',
            'months' => ['KAS', 'ARA', 'OCA', 'ŞUB', 'MAR'],
            'nutrition' => ['kcal' => '30', 'fat' => '0.3 g', 'carb' => '9.4 g', 'protein' => '1.1 g'],
            'char' => [
                'cekirdek' => 'Az Çekirdekli',
                'kabuk'    => 'Canlı Sarı Pürüzlü (Orta - İnce)',
                'et'       => 'Sarı',
                'tat'      => 'Zengin Aromatik Ekşi',
                'doku'     => 'Yumuşak Bol Sulu',
                'sekil'    => 'Sivri Uçlu Oval'
            ]
        ],
        [
            'name' => 'Lizbon',
            'desc' => 'Canlı sarı dayanıklı kabuk, dal tarafı halka boyunlu ve oldukça sulu.',
            'months' => ['KAS', 'ARA', 'OCA', 'ŞUB', 'MAR'],
            'nutrition' => ['kcal' => '30', 'fat' => '0.3 g', 'carb' => '9.5 g', 'protein' => '1.0 g'],
            'char' => [
                'cekirdek' => 'Az Çekirdekli',
                'kabuk'    => 'Canlı Sarı (Orta - İnce)',
                'et'       => 'Yeşilimsi Mat Sarı',
                'tat'      => 'Canlandırıcı Asidik Ekşi',
                'doku'     => 'Oldukça Sulu',
                'sekil'    => 'Dal Tarafı Halka Boyunlu, Uç Tarafı Sivri'
            ]
        ],
        [
            'name' => 'Lamas',
            'desc' => 'Geleneksel depolamaya uygun, ince parlak kabuk ve zengin limon kokusu.',
            'months' => ['KAS', 'ARA', 'OCA', 'ŞUB', 'MAR'],
            'nutrition' => ['kcal' => '31', 'fat' => '0.3 g', 'carb' => '9.2 g', 'protein' => '1.1 g'],
            'char' => [
                'cekirdek' => 'Az Çekirdekli veya Çekirdeksiz',
                'kabuk'    => 'Parlak Sarı (İnce)',
                'et'       => 'Sarı',
                'tat'      => 'Zengin Aromatik Ekşi',
                'doku'     => 'Oldukça Sulu',
                'sekil'    => 'Uzun Silindirik, Uç Tarafı Sivri Hafif Boyun Halkalı'
            ]
        ],
    ],
    'akdeniz-karpuzu' => [
        [
            'name' => 'Akdeniz Karpuzu - Select',
            'desc' => 'Özenle seçilmiş, birinci sınıf ihraç kalibresi, yüksek şeker oranlı taze mahsul.',
            'months' => ['MAY', 'HAZ', 'TEM', 'AĞU'],
            'nutrition' => ['kcal' => '30', 'fat' => '0.2 g', 'carb' => '7.6 g', 'protein' => '0.6 g'],
            'char' => ['et' => 'Canlı Kırmızı & Gevrek', 'cekirdek' => 'Az Çekirdekli', 'kabuk' => 'Açık & Koyu Yeşil Çizgili', 'doku' => 'Bol Sulu & Kıtır', 'sekil' => 'Düzgün Oval Kalibre', 'tat' => 'Yüksek Brix Tatlı']
        ],
        [
            'name' => 'Çekirdeksiz Mini Karpuz',
            'desc' => 'Yüksek brix tatlılık oranı, çekirdeksiz yapı ve pratik ihracat kalibresi.',
            'months' => ['HAZ', 'TEM', 'AĞU'],
            'nutrition' => ['kcal' => '32', 'fat' => '0.1 g', 'carb' => '8.0 g', 'protein' => '0.7 g'],
            'char' => ['et' => 'Koyu Yakut Kırmızısı', 'cekirdek' => '%100 Çekirdeksiz', 'kabuk' => 'İnce Koyu Yeşil Kabuk', 'doku' => 'Yoğun Şekerli ve Gevrek', 'sekil' => 'Kompakt Yuvarlak', 'tat' => 'Ekstra Tatlı & Ferah']
        ],
        [
            'name' => 'Akdeniz Karpuzu - Standart',
            'desc' => 'Global standartlarda kalibrasyon, kalın kabuk ve soğuk hava koruması.',
            'months' => ['MAY', 'HAZ', 'TEM', 'AĞU'],
            'nutrition' => ['kcal' => '30', 'fat' => '0.2 g', 'carb' => '7.5 g', 'protein' => '0.6 g'],
            'char' => ['et' => 'Açık Kırmızı Etli', 'cekirdek' => 'Normal Çekirdekli', 'kabuk' => 'Kalın Dayanıklı Kabuk', 'doku' => 'Sulu & Taze', 'sekil' => 'Uzun Oval', 'tat' => 'Klasik Doğal Karpuz']
        ],
    ],
    'salkim-domates' => [
        [
            'name' => 'Salkım Kokteyl Domates',
            'desc' => 'Dalında kızaran, yüksek brix aromalı ve sıkı dokulu taze ihracat domatesi.',
            'months' => ['AĞU', 'EYL', 'EKİ', 'KAS', 'ARA', 'OCA', 'ŞUB', 'MAR', 'NİS', 'MAY', 'HAZ', 'TEM'],
            'nutrition' => ['kcal' => '18', 'fat' => '0.2 g', 'carb' => '3.9 g', 'protein' => '0.9 g'],
            'char' => ['et' => 'Parlak Kırmızı Etli', 'cekirdek' => 'Doğal Jel Çekirdekli', 'kabuk' => 'Parlak İnce Dayanıklı', 'doku' => 'Gevrek, Çatlamayan Yapı', 'sekil' => 'Kusursuz Yuvarlak Salkım', 'tat' => 'Yoğun Tatlı Domates Kokusu']
        ],
        [
            'name' => 'Salkım Beef Domates',
            'desc' => 'Etli, dolgun dilimleme randımanı ve geleneksel nefis domates kokusu.',
            'months' => ['AĞU', 'EYL', 'EKİ', 'KAS', 'ARA', 'OCA', 'ŞUB', 'MAR', 'NİS', 'MAY', 'HAZ', 'TEM'],
            'nutrition' => ['kcal' => '20', 'fat' => '0.2 g', 'carb' => '4.2 g', 'protein' => '1.0 g'],
            'char' => ['et' => 'Etli & Çok Odacıklı', 'cekirdek' => 'Az Çekirdekli', 'kabuk' => 'Canlı Kırmızı Düzgün', 'doku' => 'Sıkı & Dolgun Dilimlik', 'sekil' => 'Hafif Dilimli İri Form', 'tat' => 'Geleneksel Tarla Aroması']
        ],
    ],
    'bugday' => [
        [
            'name' => 'Sert Durum Makarnalık Buğday',
            'desc' => 'Yüksek camsılık, zengin sarı pigment ve üstün protein değeriyle makarna sanayisinin lideri.',
            'months' => ['HAZ', 'TEM', 'AĞU'],
            'nutrition' => ['kcal' => '339', 'fat' => '2.5 g', 'carb' => '71.0 g', 'protein' => '13.5 g'],
            'char' => ['et' => 'Amber Sarısı Camsı Dane', 'cekirdek' => 'Sert Endosperm', 'kabuk' => 'İnce Altın Kepek', 'doku' => 'Yüksek İrmik Verimi', 'sekil' => 'İri Dolgun Tane', 'tat' => 'Doğal Tahıl Lezzeti']
        ],
        [
            'name' => 'Kırmızı Sert Ekmeklik Buğday',
            'desc' => 'Yüksek gluten indeksi, mükemmel un randımanı ve üstün kabarma kabiliyeti.',
            'months' => ['HAZ', 'TEM', 'AĞU'],
            'nutrition' => ['kcal' => '330', 'fat' => '2.0 g', 'carb' => '70.0 g', 'protein' => '12.8 g'],
            'char' => ['et' => 'Kırmızımsı Kahve Tane', 'cekirdek' => 'Homojen Sertlik', 'kabuk' => 'Doğal Zengin Kepek', 'doku' => 'Mükemmel Un Randımanı', 'sekil' => 'Uzun Oval Tane', 'tat' => 'Zengin Ekmeklik Aroma']
        ],
        [
            'name' => 'Beyaz Sert Buğday',
            'desc' => 'Açık renkli un ve bisküvilik sanayi için homojen hektolitre ağırlığı.',
            'months' => ['HAZ', 'TEM', 'AĞU'],
            'nutrition' => ['kcal' => '335', 'fat' => '2.1 g', 'carb' => '71.0 g', 'protein' => '12.0 g'],
            'char' => ['et' => 'Berrak Beyaz Dane', 'cekirdek' => 'Dengeli Yumuşaklık', 'kabuk' => 'Açık Sarı Kepek', 'doku' => 'Yüksek Un Beyazlığı', 'sekil' => 'Yuvarlak Dolgun', 'tat' => 'Hafif Tatlımsı Tahıl']
        ],
    ],
    'misir' => [
        [
            'name' => 'Sarı At Dişi Tane Mısır',
            'desc' => 'Yüksek nişasta ve metabolik enerji içeriği, düşük kırık ve ideal nem kalibrasyonu.',
            'months' => ['AĞU', 'EYL', 'EKİ'],
            'nutrition' => ['kcal' => '365', 'fat' => '4.7 g', 'carb' => '74.0 g', 'protein' => '9.4 g'],
            'char' => ['et' => 'Parlak Altın Sarısı', 'cekirdek' => 'Geniş Embriyo', 'kabuk' => 'Pürüzsüz Sarı Kabuk', 'doku' => 'Sert ve Dolgun Tane', 'sekil' => 'At Dişi Kalibre', 'tat' => 'Doğal Yüksek Nişasta']
        ],
        [
            'name' => 'Sert Flint Mısır',
            'desc' => 'Kusursuz tane sertliği, uzun depolama ömrü ve gıda sanayisi için ideal un kalitesi.',
            'months' => ['AĞU', 'EYL', 'EKİ'],
            'nutrition' => ['kcal' => '360', 'fat' => '4.5 g', 'carb' => '73.0 g', 'protein' => '9.8 g'],
            'char' => ['et' => 'Koyu Turuncumsu Sarı', 'cekirdek' => 'Ekstra Sert Camsı', 'kabuk' => 'Dayanıklı Parlak Perikarp', 'doku' => 'Kırılmaya Karşı Dirençli', 'sekil' => 'Küremsi Yuvarlak', 'tat' => 'Zengin Mısır Aroması']
        ],
    ],
    'aycekirdegi' => [
        [
            'name' => 'Dakota Alacalı Çerezlik',
            'desc' => 'İri boy kalibre, yüksek doluluk oranı ve kolay kavurma randımanıyla sofralık lider.',
            'months' => ['TEM', 'AĞU', 'EYL'],
            'nutrition' => ['kcal' => '584', 'fat' => '51.5 g', 'carb' => '20.0 g', 'protein' => '20.8 g'],
            'char' => ['et' => 'Krem Beyazı Dolgun İç', 'cekirdek' => '%50+ İç Randımanı', 'kabuk' => 'Siyah-Beyaz Alacalı Çizgili', 'doku' => 'Gevrek ve Yağlı', 'sekil' => 'Uzun İri Kalibre', 'tat' => 'Lezzetli Kavurmalık']
        ],
        [
            'name' => 'Siyah Yağlık Ayçekirdeği',
            'desc' => 'Yüksek yağ randımanı (%45+), ince kabuk yapısı ve üstün bitkisel yağ kalitesi.',
            'months' => ['TEM', 'AĞU', 'EYL'],
            'nutrition' => ['kcal' => '590', 'fat' => '53.0 g', 'carb' => '18.0 g', 'protein' => '21.0 g'],
            'char' => ['et' => 'Açık Sarı Yağlı İç', 'cekirdek' => 'Maksimum Yağ Oranı', 'kabuk' => 'Tam Siyah İnce Kabuk', 'doku' => 'Kolay Kırılan Sıkı Tane', 'sekil' => 'Küçük Oval Kalibre', 'tat' => 'Yüksek Yağ Konsantrasyonu']
        ],
    ],
    'soya-fasulyesi' => [
        [
            'name' => 'Birinci Ürün Sarı Soya',
            'desc' => 'Non-GMO kontrollü tarım, zengin bitkisel protein ve ideal yağ asidi dengesi.',
            'months' => ['AĞU', 'EYL', 'EKİ'],
            'nutrition' => ['kcal' => '446', 'fat' => '19.9 g', 'carb' => '30.2 g', 'protein' => '36.5 g'],
            'char' => ['et' => 'Açık Sarı Embriyo', 'cekirdek' => '%36+ Yüksek Ham Protein', 'kabuk' => 'Pürüzsüz Açık Sarı Kabuk', 'doku' => 'Sert Küre Form', 'sekil' => 'Düzgün Yuvarlak Tane', 'tat' => 'Karakteristik Soya Proteini']
        ],
        [
            'name' => 'İkinci Ürün Yüksek Protein Soya',
            'desc' => 'Çukurova’da hasat edilen, yüksek protein ve üstün yağ randımanı sağlayan taze mahsul.',
            'months' => ['EYL', 'EKİ'],
            'nutrition' => ['kcal' => '440', 'fat' => '20.5 g', 'carb' => '30.0 g', 'protein' => '35.8 g'],
            'char' => ['et' => 'Altın Sarısı Dolgun', 'cekirdek' => '%19+ Doğal Yağ Oranı', 'kabuk' => 'Temiz Parlak Kabuk', 'doku' => 'Homojen Boylanmış Tane', 'sekil' => 'Küremsi Kalibre', 'tat' => 'Doğal Bitkisel Yağ & Protein']
        ],
    ],
];

// Fallback: Ürünün veritabanındaki kayıtlı hasat dönemine göre dinamik aylar
$dbHarvestText = $product['harvest_season_tr'] ?? 'Mayıs - Ağustos';
$computedMonths = resolveHarvestMonths($dbHarvestText);

// Dinamik Ürün Ekstra Verileri (Arka Panelden Yönetilen)
$prodExtra = $product['extra'] ?? (!empty($product['extra_json']) ? json_decode($product['extra_json'], true) : []);

// Ürün Cinsleri: Öncelik Arka Panelden Kaydedilen Cinslerdedir, Yoksa Standart Listeden Alınır
if (!empty($prodExtra['varieties']) && is_array($prodExtra['varieties']) && count($prodExtra['varieties']) > 0) {
    $productVarieties = [];
    foreach ($prodExtra['varieties'] as $v) {
        $mRaw = !empty($v['months']) ? $v['months'] : $dbHarvestText;
        $vMonths = is_array($mRaw) ? $mRaw : resolveHarvestMonths((string)$mRaw);
        $productVarieties[] = [
            'name'      => $v['name'],
            'desc'      => $v['desc'] ?? '',
            'months'    => $vMonths,
            'nutrition' => [
                'kcal'    => !empty($v['nutrition']['kcal']) ? $v['nutrition']['kcal'] : ($prodExtra['nutrition_calorie'] ?? '47 kcal'),
                'fat'     => !empty($v['nutrition']['fat']) ? $v['nutrition']['fat'] : '0.1 g',
                'carb'    => !empty($v['nutrition']['carb']) ? $v['nutrition']['carb'] : '11.8 g',
                'protein' => !empty($v['nutrition']['protein']) ? $v['nutrition']['protein'] : '0.9 g'
            ],
            'char'      => [
                'et'       => !empty($v['char']['et']) ? $v['char']['et'] : (!empty($v['char_note']) ? $v['char_note'] : ($prodExtra['char_et_rengi'] ?? '')),
                'cekirdek' => !empty($v['char']['cekirdek']) ? $v['char']['cekirdek'] : ($prodExtra['char_cekirdek'] ?? ''),
                'kabuk'    => !empty($v['char']['kabuk']) ? $v['char']['kabuk'] : ($prodExtra['char_kabuk_rengi'] ?? ''),
                'doku'     => !empty($v['char']['doku']) ? $v['char']['doku'] : ($prodExtra['char_dokusu'] ?? ''),
                'sekil'    => !empty($v['char']['sekil']) ? $v['char']['sekil'] : ($prodExtra['char_sekil'] ?? ''),
                'tat'      => !empty($v['char']['tat']) ? $v['char']['tat'] : ($prodExtra['char_tadi'] ?? '')
            ]
        ];
    }
} else {
    $productVarieties = $varietiesData[$slug] ?? [
        ['name' => $prodName . ' - Select', 'desc' => 'Özenle seçilmiş, birinci sınıf ihraç kalibresi taze mahsul.', 'months' => $computedMonths],
        ['name' => $prodName . ' - Standart', 'desc' => 'Global standartlarda kalibrasyon ve soğuk hava koruması.', 'months' => $computedMonths],
    ];
}

// Tüm 12 Ay Listesi
$allMonths = ['AĞU', 'EYL', 'EKİ', 'KAS', 'ARA', 'OCA', 'ŞUB', 'MAR', 'NİS', 'MAY', 'HAZ', 'TEM'];

// Ürüne Özel Meyve Karakteristikleri (Veritabanında Yoksa Slug Varsayılanları)
$characteristicsData = [
    'star-ruby-greyfurt' => [
        'et_rengi' => ['title' => 'Et Rengi', 'val' => 'Koyu Kırmızı', 'icon' => '🎨'],
        'cekirdek' => ['title' => 'Çekirdek Yapısı', 'val' => '1 - 2 Çekirdek', 'icon' => '🌱'],
        'kabuk_rengi' => ['title' => 'Kabuk Rengi', 'val' => 'Pembemsi Turuncu, Kırmızı Yanaklı', 'icon' => '🍊'],
        'dokusu' => ['title' => 'Dokusu & Sululuk', 'val' => 'Yumuşak Bol Sulu', 'icon' => '💧'],
        'sekil' => ['title' => 'Meyve Şekli', 'val' => 'Yuvarlak, Hafif Basık', 'icon' => '✨'],
        'tadi' => ['title' => 'Tadı & Asidite', 'val' => 'Tatlı ve Aromatik', 'icon' => '🍯'],
    ],
    'white-marsh-seedless' => [
        'et_rengi' => ['title' => 'Et Rengi', 'val' => 'Mat Sarı', 'icon' => '🎨'],
        'cekirdek' => ['title' => 'Çekirdek Yapısı', 'val' => 'Çekirdeksiz', 'icon' => '🌱'],
        'kabuk_rengi' => ['title' => 'Kabuk Rengi', 'val' => 'Pürüzsüz, Parlak Açık Sarı', 'icon' => '🍊'],
        'dokusu' => ['title' => 'Dokusu & Sululuk', 'val' => 'Diri, Bol Sulu', 'icon' => '💧'],
        'sekil' => ['title' => 'Meyve Şekli', 'val' => 'Yuvarlak, Hafif Basık', 'icon' => '✨'],
        'tadi' => ['title' => 'Tadı & Asidite', 'val' => 'Ferahlatıcı Ekşi & Aromatik', 'icon' => '🍯'],
    ],
    'riored-greyfurt' => [
        'et_rengi' => ['title' => 'Et Rengi', 'val' => 'Koyu Kırmızı', 'icon' => '🎨'],
        'cekirdek' => ['title' => 'Çekirdek Yapısı', 'val' => '1 - 2 Çekirdek', 'icon' => '🌱'],
        'kabuk_rengi' => ['title' => 'Kabuk Rengi', 'val' => 'Kırmızımsı - Turuncu Kırmızı Yanaklı', 'icon' => '🍊'],
        'dokusu' => ['title' => 'Dokusu & Sululuk', 'val' => 'Su Oranı Yüksek, Tatlı ve Lezzetli', 'icon' => '💧'],
        'sekil' => ['title' => 'Meyve Şekli', 'val' => 'Yuvarlak, Hafif Basık', 'icon' => '✨'],
        'tadi' => ['title' => 'Tadı & Asidite', 'val' => 'Tatlı ve Lezzetli', 'icon' => '🍯'],
    ],
    'washington-portakal' => [
        'et_rengi' => ['title' => 'Et Rengi', 'val' => 'Turuncu', 'icon' => '🎨'],
        'cekirdek' => ['title' => 'Çekirdek Yapısı', 'val' => '1 - 2 Çekirdek', 'icon' => '🌱'],
        'kabuk_rengi' => ['title' => 'Kabuk Rengi', 'val' => 'Koyu Turuncu', 'icon' => '🍊'],
        'dokusu' => ['title' => 'Dokusu & Sululuk', 'val' => 'Gevrek, Bol Sulu', 'icon' => '💧'],
        'sekil' => ['title' => 'Meyve Şekli', 'val' => 'Yuvarlağa Yakın', 'icon' => '✨'],
        'tadi' => ['title' => 'Tadı & Asidite', 'val' => 'Tatlı, Aromatik', 'icon' => '🍯'],
    ],
    'mandalina' => [
        'et_rengi' => ['title' => 'Et Rengi', 'val' => 'Koyu Turuncu', 'icon' => '🎨'],
        'cekirdek' => ['title' => 'Çekirdek Yapısı', 'val' => 'Çekirdeksiz', 'icon' => '🌱'],
        'kabuk_rengi' => ['title' => 'Kabuk Rengi', 'val' => 'Turuncu, Sarı', 'icon' => '🍊'],
        'dokusu' => ['title' => 'Dokusu & Sululuk', 'val' => 'Bol Sulu, Etli', 'icon' => '💧'],
        'sekil' => ['title' => 'Meyve Şekli', 'val' => 'Yassı Yuvarlak', 'icon' => '✨'],
        'tadi' => ['title' => 'Tadı & Asidite', 'val' => 'Tatlı, Çok Lezzetli', 'icon' => '🍯'],
    ],
    'mayer-limon' => [
        'et_rengi' => ['title' => 'Et Rengi', 'val' => 'Yeşilimsi Sarı', 'icon' => '🎨'],
        'cekirdek' => ['title' => 'Çekirdek Yapısı', 'val' => 'Az Çekirdekli', 'icon' => '🌱'],
        'kabuk_rengi' => ['title' => 'Kabuk Rengi', 'val' => 'Koyu Sarı (Orta - İnce)', 'icon' => '🍋'],
        'dokusu' => ['title' => 'Dokusu & Sululuk', 'val' => 'Bol Sulu', 'icon' => '💧'],
        'sekil' => ['title' => 'Meyve Şekli', 'val' => 'Silindirik, Sap Tarafından Kısa Boyunlu', 'icon' => '✨'],
        'tadi' => ['title' => 'Tadı & Asidite', 'val' => 'Canlandırıcı Asidik / Ekşi', 'icon' => '🍯'],
    ],
    'bugday' => [
        'et_rengi' => ['title' => 'Tane / Et Rengi', 'val' => 'Amber Sarısı Camsı Dane', 'icon' => '🌾'],
        'cekirdek' => ['title' => 'Endosperm / Protein', 'val' => 'Sert Endosperm & Yüksek Protein', 'icon' => '🔬'],
        'kabuk_rengi' => ['title' => 'Kepek / Kabuk', 'val' => 'İnce Altın Kepek', 'icon' => '🍞'],
        'dokusu' => ['title' => 'Un / İrmik Randımanı', 'val' => 'Yüksek İrmik & Un Verimi', 'icon' => '⚙️'],
        'sekil' => ['title' => 'Tane Şekli', 'val' => 'İri Dolgun Dane Formu', 'icon' => '✨'],
        'tadi' => ['title' => 'Aroma & Kalite', 'val' => 'Doğal Tahıl Lezzeti', 'icon' => '🏆'],
    ],
    'misir' => [
        'et_rengi' => ['title' => 'Tane Rengi', 'val' => 'Parlak Altın Sarısı', 'icon' => '🌽'],
        'cekirdek' => ['title' => 'Embriyo / Yapı', 'val' => 'Geniş Embriyo & Yüksek Enerji', 'icon' => '🔬'],
        'kabuk_rengi' => ['title' => 'Perikarp / Kabuk', 'val' => 'Pürüzsüz Sarı Parlak Kabuk', 'icon' => '✨'],
        'dokusu' => ['title' => 'Tane Sertliği', 'val' => 'Sert & Dolgun Tane', 'icon' => '⚙️'],
        'sekil' => ['title' => 'Tane Kalibresi', 'val' => 'At Dişi & Küremsi Kalibre', 'icon' => '✨'],
        'tadi' => ['title' => 'Nişasta & Kalite', 'val' => 'Doğal Yüksek Nişasta & Aroma', 'icon' => '🏆'],
    ],
    'aycekirdegi' => [
        'et_rengi' => ['title' => 'İç Rengi', 'val' => 'Krem Beyazı / Açık Sarı', 'icon' => '🌻'],
        'cekirdek' => ['title' => 'İç Randımanı', 'val' => '%50+ Dolgun İç Randımanı', 'icon' => '🔬'],
        'kabuk_rengi' => ['title' => 'Kabuk Deseni', 'val' => 'Alacalı Çizgili / Tam Siyah', 'icon' => '✨'],
        'dokusu' => ['title' => 'Tane Dokusu', 'val' => 'Gevrek ve Yağlı', 'icon' => '💧'],
        'sekil' => ['title' => 'Kalibre Şekli', 'val' => 'Uzun İri Kalibre Form', 'icon' => '✨'],
        'tadi' => ['title' => 'Kavurmalık Lezzet', 'val' => 'Lezzetli ve Zengin Yağ Aroması', 'icon' => '🏆'],
    ],
    'soya-fasulyesi' => [
        'et_rengi' => ['title' => 'Embriyo Rengi', 'val' => 'Açık Altın Sarısı', 'icon' => '🌱'],
        'cekirdek' => ['title' => 'Bitkisel Protein', 'val' => '%36+ Yüksek Ham Protein', 'icon' => '🔬'],
        'kabuk_rengi' => ['title' => 'Kabuk Yapısı', 'val' => 'Pürüzsüz Açık Sarı Kabuk', 'icon' => '✨'],
        'dokusu' => ['title' => 'Tane Dokusu', 'val' => 'Sert, Homojen Boylanmış Tane', 'icon' => '⚙️'],
        'sekil' => ['title' => 'Kalibre Formu', 'val' => 'Düzgün Yuvarlak Küre Kalibre', 'icon' => '✨'],
        'tadi' => ['title' => 'Besin Değeri', 'val' => 'Doğal Bitkisel Protein & Yağ', 'icon' => '🏆'],
    ],
    'akdeniz-karpuzu' => [
        'et_rengi' => ['title' => 'Et Rengi', 'val' => 'Koyu Kırmızı & Gevrek', 'icon' => '🍉'],
        'cekirdek' => ['title' => 'Çekirdek Yapısı', 'val' => 'Küçük Siyah Çekirdekli', 'icon' => '🌱'],
        'kabuk_rengi' => ['title' => 'Kabuk Rengi', 'val' => 'Açık Yeşil Çizgili Parlak', 'icon' => '✨'],
        'dokusu' => ['title' => 'Dokusu & Sululuk', 'val' => 'Ekstra Sulu, Gevrek ve Lifli', 'icon' => '💧'],
        'sekil' => ['title' => 'Meyve Şekli', 'val' => 'Blok Yuvarlak & Oval Kalibre', 'icon' => '✨'],
        'tadi' => ['title' => 'Tadı & Brix', 'val' => 'Yüksek Şeker Oranlı Tatlı (11+ Brix)', 'icon' => '🍯'],
    ],
    'salkim-domates' => [
        'et_rengi' => ['title' => 'Et Rengi', 'val' => 'Canlı Parlak Kırmızı', 'icon' => '🍅'],
        'cekirdek' => ['title' => 'Çekirdek Yapısı', 'val' => 'Doğal Jel Çekirdekli', 'icon' => '🌱'],
        'kabuk_rengi' => ['title' => 'Kabuk Rengi', 'val' => 'Koyu Kırmızı & Parlak İnce', 'icon' => '✨'],
        'dokusu' => ['title' => 'Dokusu', 'val' => 'Sıkı Dokulu, Çatlamaya Dayanıklı', 'icon' => '💧'],
        'sekil' => ['title' => 'Meyve Şekli', 'val' => 'Kusursuz Yuvarlak Salkım Kalibre', 'icon' => '✨'],
        'tadi' => ['title' => 'Tadı & Aroma', 'val' => 'Geleneksel Aromatik Tatlı Domates Kokusu', 'icon' => '🍯'],
    ],
];

$defChar = $characteristicsData[$slug] ?? [];
$isGrain = str_contains(mb_strtolower($catName, 'UTF-8'), 'hububat') || in_array($slug, ['bugday', 'misir', 'aycekirdegi', 'soya-fasulyesi'], true);

$characteristics = [
    'et_rengi'    => ['title' => $isGrain ? 'Tane / Et Rengi' : 'Et Rengi', 'val' => !empty($prodExtra['char_et_rengi']) ? $prodExtra['char_et_rengi'] : ($defChar['et_rengi']['val'] ?? ($isGrain ? 'Amber Sarısı Camsı Dane' : 'Pembemsi Turuncu')), 'icon' => $isGrain ? '🌾' : '🎨'],
    'cekirdek'    => ['title' => $isGrain ? 'Dane / Protein Yapısı' : 'Çekirdek Yapısı', 'val' => !empty($prodExtra['char_cekirdek']) ? $prodExtra['char_cekirdek'] : ($defChar['cekirdek']['val'] ?? ($isGrain ? 'Sert Endosperm & Yüksek Protein' : 'Çekirdeksiz')), 'icon' => $isGrain ? '🔬' : '🌱'],
    'kabuk_rengi' => ['title' => $isGrain ? 'Kepek / Kabuk' : 'Kabuk Rengi', 'val' => !empty($prodExtra['char_kabuk_rengi']) ? $prodExtra['char_kabuk_rengi'] : ($defChar['kabuk_rengi']['val'] ?? ($isGrain ? 'İnce Altın Kepek' : 'Turuncu')), 'icon' => $isGrain ? '🍞' : '🍊'],
    'dokusu'      => ['title' => $isGrain ? 'Un / İrmik Verimi' : 'Dokusu & Sululuk', 'val' => !empty($prodExtra['char_dokusu']) ? $prodExtra['char_dokusu'] : ($defChar['dokusu']['val'] ?? ($isGrain ? 'Yüksek İrmik & Un Verimi' : 'Gevrek ve Su Oranı Yüksek')), 'icon' => $isGrain ? '⚙️' : '💧'],
    'sekil'       => ['title' => $isGrain ? 'Tane Formu' : 'Meyve Şekli', 'val' => !empty($prodExtra['char_sekil']) ? $prodExtra['char_sekil'] : ($defChar['sekil']['val'] ?? ($isGrain ? 'İri Dolgun Dane' : 'Düzgün Yuvarlak Şekilli')), 'icon' => '✨'],
    'tadi'        => ['title' => $isGrain ? 'Aroma & Kalite Notu' : 'Tadı & Asidite', 'val' => !empty($prodExtra['char_tadi']) ? $prodExtra['char_tadi'] : ($defChar['tadi']['val'] ?? ($isGrain ? 'Doğal Tahıl Lezzeti' : 'Tatlı ve Aromatik')), 'icon' => $isGrain ? '🏆' : '🍯'],
];

// Dinamik Ambalaj ve Kutu Seçenekleri (Arka Panelden Yönetilen)
$box1 = [
    'title'  => !empty($prodExtra['box1_title']) ? $prodExtra['box1_title'] : 'Karton Kutu',
    'weight' => !empty($prodExtra['box1_weight']) ? $prodExtra['box1_weight'] : '7 Kg',
    'dim'    => !empty($prodExtra['box1_dim']) ? $prodExtra['box1_dim'] : '600 x 400 x 120',
    'image'  => !empty($prodExtra['box1_image']) ? $prodExtra['box1_image'] : 'packaging_carton_1.png'
];
$box2 = [
    'title'  => !empty($prodExtra['box2_title']) ? $prodExtra['box2_title'] : 'Karton Kutu',
    'weight' => !empty($prodExtra['box2_weight']) ? $prodExtra['box2_weight'] : '15 Kg',
    'dim'    => !empty($prodExtra['box2_dim']) ? $prodExtra['box2_dim'] : '600 x 400 x 160',
    'image'  => !empty($prodExtra['box2_image']) ? $prodExtra['box2_image'] : 'packaging_carton_2.png'
];
$box3 = [
    'title'  => !empty($prodExtra['box3_title']) ? $prodExtra['box3_title'] : 'Karton Kutu Derin',
    'weight' => !empty($prodExtra['box3_weight']) ? $prodExtra['box3_weight'] : '15 Kg',
    'dim'    => !empty($prodExtra['box3_dim']) ? $prodExtra['box3_dim'] : '600 x 400 x 180',
    'image'  => !empty($prodExtra['box3_image']) ? $prodExtra['box3_image'] : 'packaging_carton_3.png'
];
$box4 = [
    'title'  => !empty($prodExtra['box4_title']) ? $prodExtra['box4_title'] : 'Karton Kutu – Teleskopik',
    'weight' => !empty($prodExtra['box4_weight']) ? $prodExtra['box4_weight'] : '15 Kg',
    'dim'    => !empty($prodExtra['box4_dim']) ? $prodExtra['box4_dim'] : '600 x 400 x 180',
    'image'  => !empty($prodExtra['box4_image']) ? $prodExtra['box4_image'] : 'packaging_telescopic.png'
];
$box5 = [
    'title'  => !empty($prodExtra['box5_title']) ? $prodExtra['box5_title'] : 'File Ambalaj',
    'weight' => !empty($prodExtra['box5_weight']) ? $prodExtra['box5_weight'] : '1.5 Kg - 2 Kg',
    'dim'    => !empty($prodExtra['box5_dim']) ? $prodExtra['box5_dim'] : 'Perakende Market Rafı',
    'image'  => !empty($prodExtra['box5_image']) ? $prodExtra['box5_image'] : 'packaging_mesh.png'
];

$badge1 = !empty($prodExtra['badge1']) ? $prodExtra['badge1'] : 'C Vitamini Deposu';
$badge2 = !empty($prodExtra['badge2']) ? $prodExtra['badge2'] : 'Yüksek Doğal Su & Lif';
$badge3 = !empty($prodExtra['badge3']) ? $prodExtra['badge3'] : 'Zengin Antioksidan';
?>

<div class="product-detail-page">
    <div class="container">
        
        <!-- Üst Breadcrumb -->
        <div class="detail-breadcrumb">
            <a href="<?= BASE_URL ?>/"><?= __('nav_home') ?></a>
            <span>/</span>
            <a href="<?= BASE_URL ?>/urunler"><?= __('nav_products') ?></a>
            <span>/</span>
            <span class="active-crumb"><?= htmlspecialchars($prodName) ?></span>
        </div>

        <!-- ====================================================================
             1. BÖLÜM: HERO SHOWCASE (GÖRSEL 1: TROPICAL MIX TARZI)
             ==================================================================== -->
        <section class="detail-hero-showcase">
            <div class="showcase-grid">
                
                <!-- Sol Sütun: Dairesel Renkli Backdrop, Taşan Ürün & 3D Yüzen Küreler -->
                <div class="showcase-visual-col">
                    <div class="showcase-circle-stage">
                        
                        <!-- Büyük Canlı Yeşil / Turuncu Dairesel Arka Plan -->
                        <div class="showcase-circle-bg"></div>

                        <!-- 3D Yüzen Pastel Küreler (Görseldeki Baloncuklar) -->
                        <div class="floating-bubble bubble-1"></div>
                        <div class="floating-bubble bubble-2"></div>
                        <div class="floating-bubble bubble-3"></div>

                        <!-- Vitrin Ürün Görseli (Kaydırma Yok, Tek ve Çarpıcı 3D Görsel) -->
                        <img src="<?= BASE_URL ?>/public/images/<?= htmlspecialchars($product['image'] ?: 'product_orange.png') ?>" 
                             alt="<?= htmlspecialchars($prodName) ?>" 
                             class="showcase-product-img">
                    </div>
                </div>

                <!-- Sağ Sütun: Başlık, İçerik İkonları, Besin Değerleri ve Teklif Butonu -->
                <div class="showcase-info-col">
                    
                    <span class="showcase-category-tag"><?= htmlspecialchars($catName) ?></span>
                    
                                        <div style="display:flex; align-items:center; gap:12px; flex-wrap:wrap; margin-bottom: 4px;">
                        <h1 class="showcase-product-title" id="showcaseProductTitle" style="margin-bottom:0;"><?= htmlspecialchars($prodName) ?></h1>
                        <span id="varietySelectedTag" style="display:none; background:#ecfdf5; color:#15803d; border:1px solid #86efac; padding:4px 12px; border-radius:9999px; font-size:12px; font-weight:700; align-items:center; gap:6px;">
                            <span id="varietySelectedName"></span>
                            <button type="button" onclick="resetToDefaultProduct(event)" title="Genel Ürüne Dön" style="background:none; border:none; cursor:pointer; color:#15803d; font-weight:800; font-size:14px; padding:0 2px; line-height:1;">✕</button>
                        </span>
                    </div>
                    
                    <p class="showcase-short-desc" id="showcaseShortDesc">
                        <?= htmlspecialchars($prodShort ?: 'Akdeniz ve Ege nin bereketli topraklarında yetişen, uluslararası kalite standartlarında işlenen taze mahsulümüz.') ?>
                    </p>

                    <!-- ================================================================
                         KULLANICI TALEBİ: İÇERİK & NİTELİKLER (4 YUVARLAK İKON & HOVER/MOBİL TIKLAMA)
                         ================================================================ -->
                    <div class="product-features-cluster-wrap">
                        <div class="features-cluster-heading">İçerik & Nitelikler:</div>
                        <div class="features-cluster-row" id="featuresClusterRow">
                            
                            <!-- 1. Doğal Vitamin Deposu -->
                            <div class="feature-capsule-item" 
                                 tabindex="0"
                                 data-title="Doğal C & A Vitaminleri Deposu" 
                                 data-desc="Yüksek doğal C vitamini, bağışıklığı güçlendirici doğal antioksidanlar ve canlı hücre koruması.">
                                <div class="feature-icon-bubble">
                                    <svg width="28" height="28" viewBox="0 0 36 36" fill="none">
                                        <circle cx="18" cy="20" r="14" fill="#f97316"/>
                                        <ellipse cx="15" cy="17" rx="10" ry="8" fill="#fb923c"/>
                                        <ellipse cx="13" cy="14" rx="4" ry="2.5" fill="#fdba74" opacity="0.8"/>
                                        <!-- Yeşil yaprak & sap -->
                                        <path d="M18 7 C18 4 19 3 20 2 C21 3 21 5 19 7 Z" fill="#15803d"/>
                                        <path d="M19 6 C23 4 27 5 28 8 C25 9 21 9 19 6 Z" fill="#22c55e"/>
                                    </svg>
                                </div>
                                <span class="feature-tooltip-box">
                                    <strong>Doğal Vitamin Deposu</strong>
                                    <span>Zengin C & A vitaminleri, yüksek enerji.</span>
                                </span>
                            </div>

                            <!-- 2. Bol Doğal Su & Nem Oranı -->
                            <div class="feature-capsule-item" 
                                 tabindex="0"
                                 data-title="%88+ Yüksek Doğal Su & Nem" 
                                 data-desc="Doğal mineralce zengin, dalından taze hasat edilmiş maksimum sululuk ve ferahlatıcı lezzet.">
                                <div class="feature-icon-bubble">
                                    <svg width="28" height="28" viewBox="0 0 36 36" fill="none">
                                        <path d="M18 4 C18 4 8 16 8 23 A10 10 0 0 0 28 23 C28 16 18 4 18 4 Z" fill="url(#dropGrad)"/>
                                        <ellipse cx="14" cy="22" rx="3" ry="5" transform="rotate(-30 14 22)" fill="#ffffff" opacity="0.6"/>
                                        <defs>
                                            <linearGradient id="dropGrad" x1="0%" y1="0%" x2="100%" y2="100%">
                                                <stop offset="0%" stop-color="#38bdf8"/>
                                                <stop offset="100%" stop-color="#0284c7"/>
                                            </linearGradient>
                                        </defs>
                                    </svg>
                                </div>
                                <span class="feature-tooltip-box">
                                    <strong>Yüksek Su & Nem</strong>
                                    <span>Maksimum sululuk ve ferahlatıcı mineral zenginliği.</span>
                                </span>
                            </div>

                            <!-- 3. %100 Doğal Lif & Katkısız -->
                            <div class="feature-capsule-item" 
                                 tabindex="0"
                                 data-title="%100 Doğal & Zengin Diyet Lifi" 
                                 data-desc="İyi tarım uygulamalarıyla yetiştirilmiş, sıfır kimyasal kalıntı ve sağlıklı sindirimi destekleyen taze lif yapısı.">
                                <div class="feature-icon-bubble">
                                    <svg width="28" height="28" viewBox="0 0 36 36" fill="none">
                                        <path d="M9 29 C14 22 18 16 27 7" stroke="#15803d" stroke-width="2.6" stroke-linecap="round"/>
                                        <path d="M27 7 C23 7 19 10 19 14 C23 14 27 11 27 7 Z" fill="#22c55e"/>
                                        <path d="M19 14 C15 15 13 18 13 22 C17 21 19 18 19 14 Z" fill="#16a34a"/>
                                        <path d="M22 17 C26 18 28 21 28 25 C24 24 22 21 22 17 Z" fill="#4ade80"/>
                                    </svg>
                                </div>
                                <span class="feature-tooltip-box">
                                    <strong>Doğal & Zengin Lif</strong>
                                    <span>İyi tarım uygulamaları, sıfır kalıntı ve yüksek lif.</span>
                                </span>
                            </div>

                            <!-- 4. İhracat Kalite Kalkanı -->
                            <div class="feature-capsule-item" 
                                 tabindex="0"
                                 data-title="Uluslararası Kalite & Gıda Güvenliği" 
                                 data-desc="GlobalGAP, ISO 22000 ve izlenebilir soğuk zincir protokollerine uygun sertifikalı dünya standardı.">
                                <div class="feature-icon-bubble">
                                    <svg width="28" height="28" viewBox="0 0 36 36" fill="none">
                                        <path d="M18 3 L7 7 V17 C7 24 12 30 18 33 C24 30 29 24 29 17 V7 L18 3 Z" fill="url(#shieldGrad)"/>
                                        <path d="M14 17 L17 20 L23 13" stroke="#ffffff" stroke-width="2.6" stroke-linecap="round" stroke-linejoin="round"/>
                                        <defs>
                                            <linearGradient id="shieldGrad" x1="0%" y1="0%" x2="100%" y2="100%">
                                                <stop offset="0%" stop-color="#60a5fa"/>
                                                <stop offset="100%" stop-color="#2563eb"/>
                                            </linearGradient>
                                        </defs>
                                    </svg>
                                </div>
                                <span class="feature-tooltip-box">
                                    <strong>Kalite & Güvenlik Kalkanı</strong>
                                    <span>GlobalGAP, ISO 22000 gıda güvenliği güvencesi.</span>
                                </span>
                            </div>

                        </div>

                        <!-- Mobilde tıklandığında anında beliren açıklama paneli -->
                        <div class="feature-mobile-desc-card" id="featureMobileDescCard" style="display:none;">
                            <div class="feat-mob-title" id="featMobTitle"></div>
                            <div class="feat-mob-text" id="featMobText"></div>
                        </div>
                    </div>

                    <!-- Besin Değerleri Tablosu (Görsel 1: Nutritional values:) -->
                    <div class="showcase-nutrition-block">
                        <div class="block-label">Besin Değerleri (100g):</div>
                        <div class="nutrition-card">
                            <div class="nutrition-col-values">
                                <div class="nutri-val" id="nutriKcal">47</div>
                                <div class="nutri-val" id="nutriFat">0.1 g</div>
                                <div class="nutri-val" id="nutriCarb">11.8 g</div>
                                <div class="nutri-val" id="nutriProtein">0.9 g</div>
                            </div>
                            <div class="nutrition-col-labels">
                                <div class="nutri-lbl">kcal (Enerji)</div>
                                <div class="nutri-lbl">yağ (fats)</div>
                                <div class="nutri-lbl">karbonhidrat</div>
                                <div class="nutri-lbl">protein</div>
                            </div>
                        </div>
                    </div>

                    <!-- Aksiyon / Teklif Butonu (Order Now Stili) -->
                    <div class="showcase-action-row">
                        <a href="https://wa.me/<?= preg_replace('/[^0-9]/', '', $whatsapp) ?>?text=Merhaba,%20<?= urlencode($prodName) ?>%20ürünü%20için%20ihracat%20fiyat%20ve%20yükleme%20teklifi%20almak%20istiyorum." 
                           target="_blank" 
                           onclick="openQuoteModal('<?= htmlspecialchars(addslashes($prodName)) ?>'); return false;" 
                           class="btn-order-now">
                            <?= $currentLang === 'en' ? 'Get a Quote' : 'Teklif Al' ?>
                        </a>
                        <a href="<?= BASE_URL ?>/iletisim?konu=<?= urlencode($prodName . ' İhracat Bilgisi') ?>" class="btn-detail-contact">
                            Bize Ulaşın
                        </a>
                    </div>

                </div>

                <!-- Sağ Sütun: Meyve Karakteristiği & Kalite Kriterleri Minimalist Kartı -->
                <!-- Sağ Sütun: Meyve Karakteristiği Not Kağıdı (Raptiye ile Tutturulmuş Tasarım) -->
                <div class="showcase-char-col">
                    <div class="char-minimal-panel char-note-paper">
                        
                        <!-- Köşeden Tutturulan Gerçekçi Raptiye (Pushpin) -->
                        <div class="char-note-pushpin" aria-hidden="true">
                            <svg width="30" height="34" viewBox="0 0 30 34" fill="none">
                                <ellipse cx="15" cy="30" rx="7" ry="2.5" fill="rgba(60, 40, 20, 0.22)"/>
                                <path d="M15 17 L15 29" stroke="#71717a" stroke-width="2.6" stroke-linecap="round"/>
                                <ellipse cx="15" cy="17" rx="8" ry="3" fill="#991b1b"/>
                                <ellipse cx="15" cy="16" rx="7" ry="2.2" fill="#b91c1c"/>
                                <path d="M8.5 16 L10.5 7 L19.5 7 L21.5 16 Z" fill="url(#pinShade)"/>
                                <ellipse cx="15" cy="7" rx="8" ry="3.5" fill="#ef4444"/>
                                <ellipse cx="14" cy="6.2" rx="5.5" ry="2" fill="#f87171"/>
                                <ellipse cx="13" cy="5.7" rx="2.2" ry="0.9" fill="#ffffff" opacity="0.85"/>
                                <defs>
                                    <linearGradient id="pinShade" x1="0%" y1="0%" x2="100%" y2="0%">
                                        <stop offset="0%" stop-color="#b91c1c"/>
                                        <stop offset="35%" stop-color="#ef4444"/>
                                        <stop offset="70%" stop-color="#dc2626"/>
                                        <stop offset="100%" stop-color="#991b1b"/>
                                    </linearGradient>
                                </defs>
                            </svg>
                        </div>

                        <div class="char-panel-header">
                            <h3 class="char-panel-title"><?= $isGrain ? 'Ürün & Kalite Karakteristiği' : 'Meyve Karakteristiği' ?></h3>
                            <p class="char-panel-desc"><?= $isGrain ? 'Fiziksel analiz ve kalite kriterleri' : 'Optik kalibrasyon ve kalite kriterleri' ?></p>
                        </div>

                        <div class="char-items-list">
                            <?php foreach ($characteristics as $k => $item): ?>
                                <div class="char-item-row">
                                    <div class="char-item-meta">
                                        <span class="char-item-label"><?= htmlspecialchars($item['title']) ?></span>
                                        <span class="char-item-value" data-charkey="<?= htmlspecialchars($item['val']) ?>"><?= htmlspecialchars($item['val']) ?></span>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>

                        <div class="char-panel-footer">
                            <span class="char-footer-text">%100 İzlenebilir İhracat Standardı</span>
                        </div>
                    </div>
                </div>

            </div>
        </section>

        <!-- ====================================================================
             3. BÖLÜM: ÜRÜN CİNSLERİ & 12 AYLIK HASAT SEZON TAKVİMİ (GÖRSEL 2)
             ==================================================================== -->
        <section class="season-section">
            <div class="section-badge-center">Hasat & İhracat Takvimi</div>
            <h2 class="section-title-center">Ürün Cinsleri ve Hasat Sezonları</h2>
            <p class="section-desc-center">
                Yılın dört mevsiminde tarladan dünya sofralarına kesintisiz, taze ve planlı sevkiyat.
            </p>

            <!-- Mobil ve Masaüstü Sezon Bilgi Çubuğu & Kaydırma Rehberi -->
            <div class="season-meta-bar">
                <div class="season-active-badge">
                    <span class="active-badge-dot">●</span>
                    <span>Hasat Dönemi: <strong><?= htmlspecialchars($product['harvest_season_' . $currentLang] ?? $product['harvest_season_tr'] ?? 'Mayıs - Ağustos') ?></strong></span>
                </div>
                <div class="season-swipe-indicator season-swipe-desktop-only">
                    <span class="swipe-icon">⇄</span>
                    <span>Tüm ayları görmek için sağa kaydırın</span>
                </div>
                <div class="season-mobile-hint">
                    <span>👆 Cins kartına dokunarak üstteki karakteristikleri inceleyin</span>
                </div>
            </div>

            <!-- MASAÜSTÜ TABLO GÖRÜNÜMÜ -->
            <div class="season-table-container">
                <table class="season-table">
                    <thead>
                        <tr>
                            <th class="col-season-label">SEZON / CİNS</th>
                            <?php foreach ($allMonths as $m): ?>
                                <th><?= $m ?></th>
                            <?php endforeach; ?>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($productVarieties as $idx => $v): ?>
                            <?php 
                            $vNutriJson = htmlspecialchars(json_encode($v['nutrition'] ?? ['kcal' => '47', 'fat' => '0.1 g', 'carb' => '11.8 g', 'protein' => '0.9 g']), ENT_QUOTES, 'UTF-8');
                            $vCharJson = htmlspecialchars(json_encode($v['char'] ?? []), ENT_QUOTES, 'UTF-8');
                            ?>
                            <tr class="variety-clickable-row" 
                                data-variety-name="<?= htmlspecialchars($v['name'], ENT_QUOTES, 'UTF-8') ?>"
                                data-variety-desc="<?= htmlspecialchars($v['desc'], ENT_QUOTES, 'UTF-8') ?>"
                                data-variety-nutri='<?= $vNutriJson ?>'
                                data-variety-char='<?= $vCharJson ?>'
                                onclick="selectProductVariety(this)"
                                title="<?= htmlspecialchars($v['name']) ?> cinsini seçip üstteki detayları görüntüle">
                                <td class="col-variety-info">
                                    <div style="display:flex; align-items:center; justify-content:space-between; gap:10px;">
                                        <div class="variety-name"><?= htmlspecialchars($v['name']) ?></div>
                                        <span class="variety-select-pill">Seç & İncele ➔</span>
                                    </div>
                                    <div class="variety-desc"><?= htmlspecialchars($v['desc']) ?></div>
                                </td>
                                <?php foreach ($allMonths as $m): ?>
                                    <?php $isActiveMonth = in_array($m, $v['months'], true); ?>
                                    <td class="col-month <?= $isActiveMonth ? 'active-season' : '' ?>">
                                        <?php if ($isActiveMonth): ?>
                                            <div class="season-badge-icon" title="<?= htmlspecialchars($v['name']) ?> hasat ayı">
                                                <?= $prodIcon ?>
                                            </div>
                                        <?php else: ?>
                                            <span class="season-dot-empty">·</span>
                                        <?php endif; ?>
                                    </td>
                                <?php endforeach; ?>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

            <!-- MOBİL KART GÖRÜNÜMÜ (KULLANICI TALEBİ: TABLO YERİNE KART ŞEKLİNDE) -->
            <div class="season-cards-mobile">
                <?php foreach ($productVarieties as $idx => $v): ?>
                    <?php 
                    $vNutriJson = htmlspecialchars(json_encode($v['nutrition'] ?? ['kcal' => '47', 'fat' => '0.1 g', 'carb' => '11.8 g', 'protein' => '0.9 g']), ENT_QUOTES, 'UTF-8');
                    $vCharJson = htmlspecialchars(json_encode($v['char'] ?? []), ENT_QUOTES, 'UTF-8');
                    $activeMonthsList = $v['months'] ?? [];
                    ?>
                    <div class="variety-mobile-card variety-clickable-row" 
                         data-variety-name="<?= htmlspecialchars($v['name'], ENT_QUOTES, 'UTF-8') ?>"
                         data-variety-desc="<?= htmlspecialchars($v['desc'], ENT_QUOTES, 'UTF-8') ?>"
                         data-variety-nutri='<?= $vNutriJson ?>'
                         data-variety-char='<?= $vCharJson ?>'
                         onclick="selectProductVariety(this)"
                         title="<?= htmlspecialchars($v['name']) ?> cinsini seçip üstteki detayları görüntüle">
                        
                        <div class="variety-mob-top">
                            <div class="variety-mob-header-left">
                                <span class="variety-mob-icon"><?= $prodIcon ?></span>
                                <div class="variety-mob-title"><?= htmlspecialchars($v['name']) ?></div>
                            </div>
                            <span class="variety-select-pill">Seç & İncele ➔</span>
                        </div>

                        <?php if (!empty($v['desc'])): ?>
                            <div class="variety-mob-desc"><?= htmlspecialchars($v['desc']) ?></div>
                        <?php endif; ?>

                        <!-- 12 Ay Hasat Takvimi (6 + 6 İki Satır) -->
                        <div class="variety-mob-calendar-block">
                            <div class="variety-mob-calendar-head">
                                <span class="cal-head-icon">🗓️</span>
                                <span class="cal-head-label">Hasat Sezonu:</span>
                                <strong class="cal-head-active"><?= implode(', ', $activeMonthsList) ?></strong>
                            </div>
                            <div class="variety-mob-months-row">
                                <?php foreach ($allMonths as $m): ?>
                                    <?php $isActive = in_array($m, $activeMonthsList, true); ?>
                                    <div class="mob-month-cell <?= $isActive ? 'active-month' : '' ?>">
                                        <div class="mob-month-lbl"><?= $m ?></div>
                                        <div class="mob-month-dot-box">
                                            <?php if ($isActive): ?>
                                                <span class="mob-active-icon"><?= $prodIcon ?></span>
                                            <?php else: ?>
                                                <span class="mob-inactive-dot">·</span>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>

                    </div>
                <?php endforeach; ?>
            </div>
        </section>

        <!-- ====================================================================
             4. BÖLÜM: İHRACAT AMBALAJ VE KUTU ÇEŞİTLERİ (ARKAPLANI OLMAYAN SAYDAM GÖRSELLER)
             ==================================================================== -->
        <section class="packaging-section">
            <div class="section-badge-center">Uluslararası Standartlar</div>
            <h2 class="section-title-center">İhracat Ambalaj ve Kutu Çeşitlerimiz</h2>
            <p class="section-desc-center">
                Soğuk zincire ve uzun yol nakliyesine dayanıklı, havalandırma kanallı profesyonel ambalaj çözümleri.
            </p>

            <div class="packaging-grid">
                
                <!-- 1. Kutu: Karton Kutu 7 Kg -->
                <div class="packaging-card">
                    <div class="packaging-img-holder">
                        <img src="<?= BASE_URL ?>/public/images/<?= htmlspecialchars($box1['image']) ?>" alt="<?= htmlspecialchars($box1['title']) ?>" loading="lazy">
                    </div>
                    <div class="packaging-details">
                        <h4 class="pkg-title"><?= htmlspecialchars($box1['title']) ?></h4>
                        <div class="pkg-meta"><strong>Ağırlık:</strong> <?= htmlspecialchars($box1['weight']) ?></div>
                        <div class="pkg-meta"><strong>Ebatlar(mm):</strong> <?= htmlspecialchars($box1['dim']) ?></div>
                    </div>
                </div>

                <!-- 2. Kutu: Karton Kutu 15 Kg -->
                <div class="packaging-card">
                    <div class="packaging-img-holder">
                        <img src="<?= BASE_URL ?>/public/images/<?= htmlspecialchars($box2['image']) ?>" alt="<?= htmlspecialchars($box2['title']) ?>" loading="lazy">
                    </div>
                    <div class="packaging-details">
                        <h4 class="pkg-title"><?= htmlspecialchars($box2['title']) ?></h4>
                        <div class="pkg-meta"><strong>Ağırlık:</strong> <?= htmlspecialchars($box2['weight']) ?></div>
                        <div class="pkg-meta"><strong>Ebatlar(mm):</strong> <?= htmlspecialchars($box2['dim']) ?></div>
                    </div>
                </div>

                <!-- 3. Kutu: Karton Kutu 15 Kg Derin -->
                <div class="packaging-card">
                    <div class="packaging-img-holder">
                        <img src="<?= BASE_URL ?>/public/images/<?= htmlspecialchars($box3['image']) ?>" alt="<?= htmlspecialchars($box3['title']) ?>" loading="lazy">
                    </div>
                    <div class="packaging-details">
                        <h4 class="pkg-title"><?= htmlspecialchars($box3['title']) ?></h4>
                        <div class="pkg-meta"><strong>Ağırlık:</strong> <?= htmlspecialchars($box3['weight']) ?></div>
                        <div class="pkg-meta"><strong>Ebatlar(mm):</strong> <?= htmlspecialchars($box3['dim']) ?></div>
                    </div>
                </div>

                <!-- 4. Kutu: Karton Kutu Teleskopik 15 Kg -->
                <div class="packaging-card">
                    <div class="packaging-img-holder">
                        <img src="<?= BASE_URL ?>/public/images/<?= htmlspecialchars($box4['image']) ?>" alt="<?= htmlspecialchars($box4['title']) ?>" loading="lazy">
                    </div>
                    <div class="packaging-details">
                        <h4 class="pkg-title"><?= htmlspecialchars($box4['title']) ?></h4>
                        <div class="pkg-meta"><strong>Ağırlık:</strong> <?= htmlspecialchars($box4['weight']) ?></div>
                        <div class="pkg-meta"><strong>Ebatlar(mm):</strong> <?= htmlspecialchars($box4['dim']) ?></div>
                    </div>
                </div>

                <!-- 5. Kutu: File Perakende Ambalaj -->
                <div class="packaging-card">
                    <div class="packaging-img-holder">
                        <img src="<?= BASE_URL ?>/public/images/<?= htmlspecialchars($box5['image']) ?>" alt="<?= htmlspecialchars($box5['title']) ?>" loading="lazy">
                    </div>
                    <div class="packaging-details">
                        <h4 class="pkg-title"><?= htmlspecialchars($box5['title']) ?></h4>
                        <div class="pkg-meta"><strong>Ağırlık:</strong> <?= htmlspecialchars($box5['weight']) ?></div>
                        <div class="pkg-meta"><strong>Kullanım:</strong> <?= htmlspecialchars($box5['dim']) ?></div>
                    </div>
                </div>

            </div>

            <!-- Alt Yönlendirme Linkleri (Görsel 2 Sağ Alt) -->
            <div class="detail-bottom-nav">
                <a href="<?= BASE_URL ?>/urunler" class="nav-return-btn">
                    <span>↩</span> <?= mb_strtoupper($catName, 'UTF-8') ?> LİSTESİ
                </a>
                <a href="<?= BASE_URL ?>/" class="nav-return-btn">
                    <span>🏠</span> ANA SAYFA
                </a>
            </div>

        </section>

    </div>
</div>


<!-- ====================================================================
     CİNS SEÇİMİ & İÇERİK/NİTELİKLER ETKİLEŞİM CSS VE JS MOTORU
     ==================================================================== -->
<style>
/* İçerik & Nitelikler Kümesi (Referans Görsel Tasarımına Birebir: Çerçevesiz, Şeffaf Zemin, Yuvarlak Beyaz Kabarcıklar) */
.product-features-cluster-wrap {
    margin: 18px 0 20px 0;
    padding: 0;
    background: transparent !important;
    border: none !important;
    border-radius: 0;
}
.features-cluster-heading {
    font-size: 15px;
    font-weight: 800;
    color: #0f4c5c;
    margin-bottom: 12px;
    letter-spacing: -0.2px;
    display: block;
}
.features-cluster-heading::before {
    display: none !important;
}
.features-cluster-row {
    display: flex;
    align-items: center;
    gap: 14px;
    flex-wrap: wrap;
}
.feature-capsule-item {
    position: relative;
    cursor: pointer;
    outline: none;
}
.feature-icon-bubble {
    width: 48px;
    height: 48px;
    background: #ffffff;
    border: 1px solid rgba(226, 232, 240, 0.7);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    box-shadow: 0 4px 14px rgba(15, 23, 42, 0.06);
    transition: transform 0.25s cubic-bezier(0.34, 1.56, 0.64, 1), box-shadow 0.25s ease;
}
.feature-capsule-item:hover .feature-icon-bubble,
.feature-capsule-item.active .feature-icon-bubble {
    transform: translateY(-3px) scale(1.05);
    box-shadow: 0 8px 20px rgba(15, 23, 42, 0.12);
    border-color: #cbd5e1;
}
/* Masaüstü Tooltip */
.feature-tooltip-box {
    position: absolute;
    bottom: calc(100% + 12px);
    left: 50%;
    transform: translateX(-50%) translateY(6px);
    background: #0f172a;
    color: #ffffff;
    padding: 10px 14px;
    border-radius: 12px;
    font-size: 12.5px;
    line-height: 1.35;
    white-space: nowrap;
    pointer-events: none;
    opacity: 0;
    visibility: hidden;
    transition: all 0.2s cubic-bezier(0.16, 1, 0.3, 1);
    box-shadow: 0 12px 24px rgba(0, 0, 0, 0.25);
    z-index: 100;
}
.feature-tooltip-box::after {
    content: '';
    position: absolute;
    top: 100%;
    left: 50%;
    transform: translateX(-50%);
    border-width: 6px;
    border-style: solid;
    border-color: #0f172a transparent transparent transparent;
}
.feature-tooltip-box strong {
    display: block;
    color: #38bdf8;
    font-weight: 700;
    margin-bottom: 2px;
}
.feature-capsule-item:hover .feature-tooltip-box,
.feature-capsule-item:focus .feature-tooltip-box {
    opacity: 1;
    visibility: visible;
    transform: translateX(-50%) translateY(0);
}
/* Mobil Bilgi Kartı */
.feature-mobile-desc-card {
    margin-top: 16px;
    padding: 14px 16px;
    background: #ffffff;
    border: 1px solid #bae6fd;
    border-left: 4px solid #0284c7;
    border-radius: 12px;
    box-shadow: 0 4px 14px rgba(2, 132, 199, 0.08);
    animation: fadeInQuick 0.2s ease;
}
.feat-mob-title {
    font-size: 13.5px;
    font-weight: 800;
    color: #0369a1;
    margin-bottom: 3px;
}
.feat-mob-text {
    font-size: 13px;
    color: #475569;
    line-height: 1.45;
}
@keyframes fadeInQuick {
    from { opacity: 0; transform: translateY(4px); }
    to { opacity: 1; transform: translateY(0); }
}

/* Tıklanabilir Sezon / Cins Tablo Satırları */
.variety-clickable-row {
    cursor: pointer;
    transition: all 0.2s ease;
}
.variety-clickable-row:hover {
    background: #f0fdf4 !important;
}
.variety-clickable-row.active-variety-row {
    background: #ecfdf5 !important;
    outline: 2px solid #22c55e;
    outline-offset: -2px;
}
.variety-select-pill {
    display: inline-flex;
    align-items: center;
    gap: 4px;
    font-size: 11px;
    font-weight: 700;
    color: #15803d;
    background: #dcfce7;
    padding: 3px 10px;
    border-radius: 9999px;
    border: 1px solid #86efac;
    transition: all 0.2s ease;
    white-space: nowrap;
}
.variety-clickable-row:hover .variety-select-pill {
    background: #15803d;
    color: #ffffff;
    border-color: #15803d;
}
.variety-clickable-row.active-variety-row .variety-select-pill {
    background: #166534;
    color: #ffffff;
}

/* Masaüstü vs Mobil Hasat Görünümü */
.season-cards-mobile {
    display: none;
}
.season-mobile-hint {
    display: none;
}

@media (max-width: 768px) {
    /* Mobilde Tabloyu Gizle, Kartları Göster */
    .season-table-container {
        display: none !important;
    }
    .season-swipe-desktop-only {
        display: none !important;
    }
    .season-mobile-hint {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        font-size: 12px;
        font-weight: 600;
        color: #15803d;
        background: #ecfdf5;
        padding: 6px 14px;
        border-radius: 9999px;
        border: 1px solid #a7f3d0;
        margin-top: 4px;
    }
    .season-meta-bar {
        flex-direction: column;
        align-items: center;
        gap: 8px;
        text-align: center;
    }
    .season-cards-mobile {
        display: flex;
        flex-direction: column;
        gap: 14px;
        width: 100%;
        margin-top: 16px;
    }
    .variety-mobile-card {
        background: #ffffff;
        border: 1.5px solid #e2e8f0;
        border-radius: 18px;
        padding: 16px 16px 14px 16px;
        box-shadow: 0 4px 14px rgba(15, 23, 42, 0.04);
        cursor: pointer;
        transition: all 0.22s ease;
        text-align: left;
        position: relative;
    }
    .variety-mobile-card:hover {
        border-color: #86efac;
        background: #f0fdf4;
    }
    .variety-mobile-card.active-variety-row {
        border-color: #15803d !important;
        background: #f0fdf4 !important;
        box-shadow: 0 8px 24px rgba(21, 128, 61, 0.14) !important;
        outline: 2px solid #16a34a !important;
        outline-offset: -2px;
    }
    .variety-mob-top {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 10px;
    }
    .variety-mob-header-left {
        display: flex;
        align-items: center;
        gap: 8px;
    }
    .variety-mob-icon {
        font-size: 22px;
        line-height: 1;
    }
    .variety-mob-title {
        font-size: 15.5px;
        font-weight: 800;
        color: #0f172a;
        margin: 0;
        line-height: 1.3;
    }
    .variety-mob-desc {
        font-size: 12.5px;
        color: #64748b;
        line-height: 1.45;
        margin: 8px 0 12px 0;
    }
    /* 12 Ay Hasat Takvimi (6 + 6 İki Satır Izgara) */
    .variety-mob-calendar-block {
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        border-radius: 14px;
        padding: 12px 10px 10px 10px;
        margin-top: 10px;
        margin-bottom: 0;
    }
    .variety-mob-calendar-head {
        display: flex;
        align-items: center;
        gap: 6px;
        font-size: 12.5px;
        margin-bottom: 9px;
        flex-wrap: wrap;
    }
    .cal-head-icon {
        font-size: 14px;
    }
    .cal-head-label {
        color: #64748b;
        font-weight: 600;
    }
    .cal-head-active {
        color: #15803d;
        font-weight: 800;
        background: #dcfce7;
        padding: 3px 9px;
        border-radius: 6px;
        font-size: 12px;
    }
    .variety-mob-months-row {
        display: grid;
        grid-template-columns: repeat(6, 1fr);
        gap: 6px 5px;
        background: #ffffff;
        padding: 8px 6px;
        border-radius: 10px;
        border: 1px solid #e2e8f0;
    }
    .mob-month-cell {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        padding: 6px 2px;
        border-radius: 8px;
        background: #f8fafc;
        border: 1px solid #f1f5f9;
        transition: all 0.2s ease;
    }
    .mob-month-cell.active-month {
        background: #15803d;
        border-color: #15803d;
        box-shadow: 0 2px 6px rgba(21, 128, 61, 0.25);
    }
    .mob-month-lbl {
        font-size: 10.5px;
        font-weight: 700;
        color: #64748b;
        line-height: 1;
        margin-bottom: 4px;
    }
    .mob-month-cell.active-month .mob-month-lbl {
        color: #ffffff;
        font-weight: 800;
    }
    .mob-month-dot-box {
        display: flex;
        align-items: center;
        justify-content: center;
        min-height: 16px;
    }
    .mob-active-icon {
        font-size: 12px;
        line-height: 1;
    }
    .mob-inactive-dot {
        font-size: 13px;
        color: #cbd5e1;
        line-height: 1;
    }
}

@media (max-width: 640px) {
    .feature-tooltip-box {
        display: none !important; /* Mobilde tooltip yerine alt kart kullanılır */
    }
    .feature-icon-bubble {
        width: 50px;
        height: 50px;
    }
    .features-cluster-row {
        gap: 12px;
    }
}
</style>

<script>
(function() {
    // 1. Orijinal Ürün Bilgilerini Hafızaya Al
    const origTitleEl = document.getElementById('showcaseProductTitle');
    const origDescEl = document.getElementById('showcaseShortDesc');
    const origKcalEl = document.getElementById('nutriKcal');
    const origFatEl = document.getElementById('nutriFat');
    const origCarbEl = document.getElementById('nutriCarb');
    const origProteinEl = document.getElementById('nutriProtein');

    const originalData = {
        title: origTitleEl ? origTitleEl.innerText.trim() : '',
        desc: origDescEl ? origDescEl.innerText.trim() : '',
        kcal: origKcalEl ? origKcalEl.innerText.trim() : '47',
        fat: origFatEl ? origFatEl.innerText.trim() : '0.1 g',
        carb: origCarbEl ? origCarbEl.innerText.trim() : '11.8 g',
        protein: origProteinEl ? origProteinEl.innerText.trim() : '0.9 g'
    };

    // 2. Cins Seçimi Fonksiyonu
    window.selectProductVariety = function(rowEl) {
        if (!rowEl) return;

        const vName = rowEl.getAttribute('data-variety-name') || '';
        const vDesc = rowEl.getAttribute('data-variety-desc') || '';
        const vNutriRaw = rowEl.getAttribute('data-variety-nutri') || '{}';
        const vCharRaw = rowEl.getAttribute('data-variety-char') || '{}';

        let vNutri = {};
        let vChar = {};
        try { vNutri = JSON.parse(vNutriRaw); } catch(e) {}
        try { vChar = JSON.parse(vCharRaw); } catch(e) {}

        // Üst Başlık ve Açıklamayı Güncelle
        if (origTitleEl) origTitleEl.innerText = vName;
        if (origDescEl) origDescEl.innerText = vDesc;

        // Besin Değerlerini Güncelle
        if (origKcalEl && vNutri.kcal) origKcalEl.innerText = vNutri.kcal;
        if (origFatEl && vNutri.fat) origFatEl.innerText = vNutri.fat;
        if (origCarbEl && vNutri.carb) origCarbEl.innerText = vNutri.carb;
        if (origProteinEl && vNutri.protein) origProteinEl.innerText = vNutri.protein;

        // Karakteristik Değerlerini Güncelle (varsa)
        const charRows = document.querySelectorAll('.char-item-row');
        if (charRows && Object.keys(vChar).length > 0) {
            charRows.forEach(row => {
                const label = row.querySelector('.char-item-label')?.innerText.trim().toLowerCase() || '';
                const valEl = row.querySelector('.char-item-value');
                if (!valEl) return;
                if (label.includes('et') && vChar.et) valEl.innerText = vChar.et;
                else if (label.includes('çekirdek') && vChar.cekirdek) valEl.innerText = vChar.cekirdek;
                else if (label.includes('kabuk') && vChar.kabuk) valEl.innerText = vChar.kabuk;
                else if (label.includes('doku') && vChar.doku) valEl.innerText = vChar.doku;
                else if (label.includes('şekil') && vChar.sekil) valEl.innerText = vChar.sekil;
                else if (label.includes('tat') && vChar.tat) valEl.innerText = vChar.tat;
            });
        }

        // Seçili Cins Rozetini Göster
        const tag = document.getElementById('varietySelectedTag');
        const tagName = document.getElementById('varietySelectedName');
        if (tag && tagName) {
            tagName.innerText = 'Cins: ' + vName;
            tag.style.display = 'inline-flex';
        }

        // Tabloda Aktif Satırı İşaretle
        document.querySelectorAll('.variety-clickable-row').forEach(r => r.classList.remove('active-variety-row'));
        rowEl.classList.add('active-variety-row');

        // Üst Alana Yumuşak Kaydırma
        const showcaseEl = document.querySelector('.detail-hero-showcase');
        if (showcaseEl) {
            const topPos = showcaseEl.getBoundingClientRect().top + window.pageYOffset - 90;
            window.scrollTo({ top: topPos, behavior: 'smooth' });
        }
    };

    // 3. Genel Ürüne Geri Dön
    window.resetToDefaultProduct = function(e) {
        if (e) e.stopPropagation();

        if (origTitleEl) origTitleEl.innerText = originalData.title;
        if (origDescEl) origDescEl.innerText = originalData.desc;
        if (origKcalEl) origKcalEl.innerText = originalData.kcal;
        if (origFatEl) origFatEl.innerText = originalData.fat;
        if (origCarbEl) origCarbEl.innerText = originalData.carb;
        if (origProteinEl) origProteinEl.innerText = originalData.protein;

        const tag = document.getElementById('varietySelectedTag');
        if (tag) tag.style.display = 'none';

        document.querySelectorAll('.variety-clickable-row').forEach(r => r.classList.remove('active-variety-row'));
    };

    // 4. İçerik & Nitelikler Mobil Dokunma (Click/Tap)
    const items = document.querySelectorAll('.feature-capsule-item');
    const mobCard = document.getElementById('featureMobileDescCard');
    const mobTitle = document.getElementById('featMobTitle');
    const mobText = document.getElementById('featMobText');

    items.forEach(item => {
        item.addEventListener('click', function() {
            const isAlreadyActive = this.classList.contains('active');
            items.forEach(it => it.classList.remove('active'));

            if (!isAlreadyActive) {
                this.classList.add('active');
                const title = this.getAttribute('data-title') || '';
                const desc = this.getAttribute('data-desc') || '';
                if (mobCard && mobTitle && mobText) {
                    mobTitle.innerText = title;
                    mobText.innerText = desc;
                    mobCard.style.display = 'block';
                }
            } else {
                if (mobCard) mobCard.style.display = 'none';
            }
        });
    });

})();
</script>