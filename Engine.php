<?php
declare(strict_types=1);

namespace app\images;

use claviska\SimpleImage;
use Exception;
use GdImage;

class Engine extends SimpleImage
{
    /**
     * We are overriding the method so that we can suppress various libpng warnings that are due to interlaced pngs.
     * @param string $file
     * @return $this
     * @throws Exception
     */
    public function fromFile(string $file): static
    {
        // Set fopen options.
        $sslVerify = $this->getFlag('sslVerify'); // Don't perform peer validation when true
        $opts = [
            'ssl' => [
                'verify_peer' => $sslVerify,
                'verify_peer_name' => $sslVerify,
            ],
        ];

        $fileName = $file;
        // Check if the file exists and is readable.
        $file = @file_get_contents($file, false, stream_context_create($opts));
        if ($file === false) {
            throw new Exception("File not found: $fileName", self::ERR_FILE_NOT_FOUND);
        }

        // Create image object from string
        $this->image = @imagecreatefromstring($file);

        // Get image info
        $info = @getimagesizefromstring($file);
        if ($info === false) {
            throw new Exception("Invalid image file: $fileName", self::ERR_INVALID_IMAGE);
        }
        $this->mimeType = $info['mime'];

        if (!$this->image) {
            throw new Exception('Unsupported format: ' . $this->mimeType, self::ERR_UNSUPPORTED_FORMAT);
        }

        switch ($this->mimeType) {
            case 'image/gif':
                // Copy the gif over to a true color image to preserve its transparency. This is a
                // workaround to prevent imagepalettetotruecolor() from borking transparency.
                $width = imagesx($this->image);
                $height = imagesx($this->image);

                $gif = imagecreatetruecolor((int)$width, (int)$height);
                $alpha = imagecolorallocatealpha($gif, 0, 0, 0, 127);
                imagecolortransparent($gif, $alpha ?: null);
                imagefill($gif, 0, 0, $alpha);

                imagecopy($this->image, $gif, 0, 0, 0, 0, $width, $height);
                imagedestroy($gif);
                break;
            case 'image/jpeg':
                // Load exif data from JPEG images
                if (function_exists('exif_read_data')) {
                    $this->exif = @exif_read_data('data://image/jpeg;base64,' . base64_encode($file));
                }
                break;
        }

        // Convert pallete images to true color images
        @imagepalettetotruecolor($this->image);

        return $this;
    }

    public function overlay(
        string|SimpleImage $overlay,
        string             $anchor = 'center',
        float|int          $opacity = 1,
        int                $xOffset = 0,
        int                $yOffset = 0,
        bool               $calculateOffsetFromEdge = false
    ): static
    {
        // Load overlay image
        if (!($overlay instanceof Engine)) {
            $overlay = new Engine($overlay);
        }

        // Convert opacity
        $opacity = (int)round(self::keepWithin($opacity, 0, 1) * 100);

        // Get available space
        $spaceX = $this->getWidth() - $overlay->getWidth();
        $spaceY = $this->getHeight() - $overlay->getHeight();

        // Set default center
        $x = (int)round(($spaceX / 2) + ($calculateOffsetFromEdge ? 0 : $xOffset));
        $y = (int)round(($spaceY / 2) + ($calculateOffsetFromEdge ? 0 : $yOffset));

        // Determine if top|bottom
        if (str_contains($anchor, 'top')) {
            $y = $yOffset;
        } elseif (str_contains($anchor, 'bottom')) {
            $y = $spaceY + ($calculateOffsetFromEdge ? -$yOffset : $yOffset);
        }

        // Determine if left|right
        if (str_contains($anchor, 'left')) {
            $x = $xOffset;
        } elseif (str_contains($anchor, 'right')) {
            $x = $spaceX + ($calculateOffsetFromEdge ? -$xOffset : $xOffset);
        }

        // Perform the overlay
        self::imageCopyMergeAlpha(
            $this->image,
            $overlay->image,
            $x, $y,
            0, 0,
            $overlay->getWidth(),
            $overlay->getHeight(),
            $opacity
        );

        return $this;
    }

    protected static function imageCopyMergeAlpha(GdImage $dstIm, GdImage $srcIm, int $dstX, int $dstY, int $srcX, int $srcY, int $srcW, int $srcH, int $pct): bool
    {
        // Are we merging with transparency?
        if ($pct < 100) {
            // Disable alpha blending and "colorize" the image using a transparent color
            @imagealphablending($srcIm, false);
            @imagefilter($srcIm, IMG_FILTER_COLORIZE, 0, 0, 0, round(127 * ((100 - $pct) / 100)));
        }

        @imagecopy($dstIm, $srcIm, $dstX, $dstY, $srcX, $srcY, $srcW, $srcH);

        return true;
    }
}
