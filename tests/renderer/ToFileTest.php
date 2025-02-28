<?php
declare(strict_types=1);

namespace app\tests\images\renderer;

use app\core\Folder;
use app\images\renderer\ToFile;
use AppDir;
use tests\stubs\SimpleImage;
use tests\TestCase;

class ToFileTest extends TestCase
{
    public function testCallsToFile(): void
    {
        $imageEngine = new SimpleImage();

        $renderer = new ToFile(new Folder(AppDir::absolute('tests/images/renderer')), 'test');
        $renderer->render($imageEngine);

        $this->assertStringContainsString('tests/images/renderer/test.png', $imageEngine->getFilePath());
        $this->assertSame('image/png', $imageEngine->getMimeType());
    }
}
