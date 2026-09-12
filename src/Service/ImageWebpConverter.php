<?php

namespace App\Service;

use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\File\UploadedFile;

final class ImageWebpConverter
{
    /**
     * Formats que GD sait décoder. Volontairement restrictif : sans ça, un SVG ou un
     * HEIC (photo iPhone) passerait la validation "image/*" puis ferait échouer la
     * conversion, avec une erreur 500 au lieu d'un message de formulaire clair.
     */
    public const SUPPORTED_MIME_TYPES = ['image/jpeg', 'image/png', 'image/webp', 'image/gif'];

    private const QUALITY = 82;

    public function __construct(private readonly Filesystem $filesystem)
    {
    }

    /**
     * Rend le callable attendu par l'option "upload_new" du FileUploadType d'EasyAdmin :
     * c'est lui qui doit déposer le fichier final dans $uploadDir/$fileName, à la place
     * du simple déplacement fait par défaut.
     *
     * @param int|null $height null = on contraint seulement la largeur et on garde le ratio
     */
    public function uploadCallback(int $width, ?int $height = null): \Closure
    {
        return function (UploadedFile $file, string $uploadDir, string $fileName) use ($width, $height): void {
            $this->convert(
                $file->getPathname(),
                rtrim($uploadDir, '/\\') . '/' . $fileName,
                $width,
                $height,
            );
        };
    }

    public function convert(string $sourcePath, string $targetPath, int $width, ?int $height = null): void
    {
        $contents = file_get_contents($sourcePath);
        $source = false !== $contents ? @imagecreatefromstring($contents) : false;

        if (false === $source) {
            throw new \RuntimeException("Image illisible ou format non supporté : {$sourcePath}");
        }

        $source = $this->applyExifOrientation($source, $sourcePath);

        $target = null === $height
            ? $this->resizeToWidth($source, $width)
            : $this->cropToBox($source, $width, $height);

        $this->filesystem->mkdir(\dirname($targetPath));

        if (!imagewebp($target, $targetPath, self::QUALITY)) {
            throw new \RuntimeException("Échec de l'écriture du fichier webp : {$targetPath}");
        }
    }

    /**
     * Les photos prises au téléphone portent souvent leur rotation en métadonnée EXIF,
     * que GD ignore : sans ça, elles ressortent couchées.
     */
    private function applyExifOrientation(\GdImage $image, string $sourcePath): \GdImage
    {
        $exif = @exif_read_data($sourcePath);

        return match ($exif['Orientation'] ?? 1) {
            3 => imagerotate($image, 180, 0),
            6 => imagerotate($image, -90, 0),
            8 => imagerotate($image, 90, 0),
            default => $image,
        };
    }

    private function resizeToWidth(\GdImage $source, int $width): \GdImage
    {
        $sourceWidth = imagesx($source);
        $sourceHeight = imagesy($source);

        // jamais d'agrandissement : ça n'ajoute aucun détail, seulement du poids
        $width = min($width, $sourceWidth);
        $height = (int) round($sourceHeight * $width / $sourceWidth);

        $target = $this->createCanvas($width, $height);
        imagecopyresampled($target, $source, 0, 0, 0, 0, $width, $height, $sourceWidth, $sourceHeight);

        return $target;
    }

    /**
     * Recadre au centre pour remplir la boîte demandée, comme le fait `object-fit: cover` en CSS.
     */
    private function cropToBox(\GdImage $source, int $width, int $height): \GdImage
    {
        $sourceWidth = imagesx($source);
        $sourceHeight = imagesy($source);

        $upscale = max($width / $sourceWidth, $height / $sourceHeight);
        if ($upscale > 1) {
            $width = (int) round($width / $upscale);
            $height = (int) round($height / $upscale);
        }

        $targetRatio = $width / $height;
        if ($sourceWidth / $sourceHeight > $targetRatio) {
            $cropWidth = (int) round($sourceHeight * $targetRatio);
            $cropHeight = $sourceHeight;
        } else {
            $cropWidth = $sourceWidth;
            $cropHeight = (int) round($sourceWidth / $targetRatio);
        }

        $target = $this->createCanvas($width, $height);
        imagecopyresampled(
            $target,
            $source,
            0,
            0,
            (int) round(($sourceWidth - $cropWidth) / 2),
            (int) round(($sourceHeight - $cropHeight) / 2),
            $width,
            $height,
            $cropWidth,
            $cropHeight,
        );

        return $target;
    }

    private function createCanvas(int $width, int $height): \GdImage
    {
        $canvas = imagecreatetruecolor($width, $height);
        imagealphablending($canvas, false);
        imagesavealpha($canvas, true);
        imagefill($canvas, 0, 0, imagecolorallocatealpha($canvas, 0, 0, 0, 127));

        return $canvas;
    }
}
