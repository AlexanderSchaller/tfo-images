<?php
declare(strict_types=1);

namespace app\tests\images\renderer;

use app\images\renderer\ForTwitter;
use tests\stubs\SimpleImage;
use tests\TestCase;

class ForTwitterTest extends TestCase
{
    public function testForTwitterWhenHeightIsMoreThanWidth(): void
    {
        $imageEngine = new SimpleImage();
        $renderer = new ForTwitter();
        $renderer->render(
            (new \claviska\SimpleImage())->fromNew(1, 2, '#fff'),
            $imageEngine
        );

        $layers = $imageEngine->getLayers();
        $this->assertSame($layers[0][0], $layers[0][1]);
        $this->assertSame(
            (ForTwitter::TWITTER_THUMBNAIL_SIZE + ForTwitter::PADDING),
            $layers[0][0]
        );
        $this->assertSame(
            (ForTwitter::TWITTER_THUMBNAIL_SIZE + ForTwitter::PADDING),
            $layers[0][1]
        );
        $this->assertSame(
            '#ffffff',
            $layers[0][2]
        );
        $this->assertCount(2, $layers);
        $this->assertTrue($imageEngine->getToScreenCalled());
    }

    public function testForTwitter(): void
    {
        $imageEngine = new SimpleImage();
        $renderer = new ForTwitter();
        $renderer->render(
            (new \claviska\SimpleImage())->fromNew(2, 1, '#fff'),
            $imageEngine
        );

        $layers = $imageEngine->getLayers();
        $this->assertSame($layers[0][0], $layers[0][1]);
        $this->assertSame(
            (ForTwitter::TWITTER_THUMBNAIL_SIZE + ForTwitter::PADDING),
            $layers[0][0]
        );
        $this->assertSame(
            (ForTwitter::TWITTER_THUMBNAIL_SIZE + ForTwitter::PADDING),
            $layers[0][1]
        );
        $this->assertSame(
            '#ffffff',
            $layers[0][2]
        );
        $this->assertCount(2, $layers);
        $this->assertTrue($imageEngine->getToScreenCalled());
    }
}
