<?php
declare(strict_types=1);

namespace app\tests\images\renderer;

use app\images\renderer\ToScreen;
use tests\stubs\SimpleImage;
use tests\TestCase;

class ToScreenTest extends TestCase
{
    public function testCallsToScreen(): void
    {
        $imageEngine = new SimpleImage();
        $renderer = new ToScreen();
        $renderer->render($imageEngine);

        $this->assertTrue($imageEngine->getToScreenCalled());
    }
}
