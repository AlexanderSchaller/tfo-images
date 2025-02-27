<?php
declare(strict_types=1);

namespace app\images\renderer;

use claviska\SimpleImage;

class ForTwitter implements Renderer
{
    public const int TWITTER_THUMBNAIL_SIZE = 250;
    public const int PADDING = 30;

    public function render(SimpleImage $image, SimpleImage $baseEngine = new SimpleImage()): void
    {
        /* Twitter has specific image requirements for their meta preview cards
        *      - Ratio should be 1 x 1
        *      - Minimum size is 144px x 144px
        *      - Maximum size is 4096px x 4096px
        *      - May be PNG, JPG, WEBP, or GIF
        *  https://developer.twitter.com/en/docs/twitter-for-websites/cards/overview/summary
        */
        $base = $baseEngine->fromNew(
            (self::TWITTER_THUMBNAIL_SIZE + self::PADDING),
            (self::TWITTER_THUMBNAIL_SIZE + self::PADDING),
            '#ffffff'
        );

        if ($image->getHeight() > $image->getWidth()) {
            //If only one dimension is specified, the image will be resized proportionally.
            $image->resize(null, self::TWITTER_THUMBNAIL_SIZE);
        } else {
            $image->resize(self::TWITTER_THUMBNAIL_SIZE, null);
        }

        $base->overlay($image);
        $base->toScreen();
    }
}
