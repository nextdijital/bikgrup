<?php
/**
 * BİKGRUP - Görsel Yükleme ve Otomatik WebP Dönüştürücü Servisi
 * PHP 8.2 / GD Uyumlu
 */

declare(strict_types=1);

class ImageUploader
{
    /**
     * $_FILES['file'] dizisinden gelen görseli alır, WebP formatına dönüştürür ve kaydeder.
     * Başarılı olursa oluşturulan yeni dosya adını (örn: prod_riored_1730000.webp) döndürür.
     */
    public static function uploadAndConvertToWebP(array $file, string $targetDir, string $prefix = 'img', int $quality = 85): ?string
    {
        if (empty($file['tmp_name']) || !isset($file['error']) || $file['error'] !== UPLOAD_ERR_OK) {
            return null;
        }

        $tmpName = $file['tmp_name'];
        if (!is_uploaded_file($tmpName)) {
            return null;
        }

        // SVG ve ICO dosyalarını doğrudan güvenli adla kaydet
        $ext = strtolower(pathinfo($file['name'] ?? '', PATHINFO_EXTENSION));
        if ($ext === 'svg' || $ext === 'ico') {
            $filename = $prefix . '_' . time() . '_' . bin2hex(random_bytes(3)) . '.' . $ext;
            if (move_uploaded_file($tmpName, rtrim($targetDir, '/\\') . '/' . $filename)) {
                return $filename;
            }
            return null;
        }

        $imageInfo = @getimagesize($tmpName);
        if ($imageInfo === false) {
            return null;
        }

        $mime = $imageInfo['mime'] ?? '';
        $image = null;

        switch ($mime) {
            case 'image/jpeg':
            case 'image/jpg':
                $image = @imagecreatefromjpeg($tmpName);
                break;
            case 'image/png':
                $image = @imagecreatefrompng($tmpName);
                if ($image) {
                    imagealphablending($image, false);
                    imagesavealpha($image, true);
                }
                break;
            case 'image/webp':
                $image = @imagecreatefromwebp($tmpName);
                break;
            case 'image/gif':
                $image = @imagecreatefromgif($tmpName);
                break;
            case 'image/bmp':
            case 'image/x-ms-bmp':
                if (function_exists('imagecreatefrombmp')) {
                    $image = @imagecreatefrombmp($tmpName);
                }
                break;
            default:
                return null;
        }

        if (!$image) {
            return null;
        }

        $cleanPrefix = preg_replace('/[^a-z0-9_-]/', '', strtolower($prefix)) ?: 'img';
        $filename = $cleanPrefix . '_' . time() . '_' . substr(bin2hex(random_bytes(4)), 0, 6) . '.webp';

        if (!is_dir($targetDir)) {
            @mkdir($targetDir, 0755, true);
        }

        $destPath = rtrim($targetDir, '/\\') . DIRECTORY_SEPARATOR . $filename;

        if (function_exists('imagewebp')) {
            $saved = @imagewebp($image, $destPath, $quality);
            imagedestroy($image);
            if ($saved && file_exists($destPath)) {
                return $filename;
            }
        } else {
            imagedestroy($image);
            $ext = strtolower(pathinfo($file['name'] ?? 'image.jpg', PATHINFO_EXTENSION)) ?: 'jpg';
            $fallbackName = $cleanPrefix . '_' . time() . '.' . $ext;
            $fallbackDest = rtrim($targetDir, '/\\') . DIRECTORY_SEPARATOR . $fallbackName;
            if (@move_uploaded_file($tmpName, $fallbackDest)) {
                return $fallbackName;
            }
        }

        return null;
    }
}

