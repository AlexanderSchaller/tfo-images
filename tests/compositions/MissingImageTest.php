<?php
declare(strict_types=1);

namespace app\tests\images\compositions;

use app\game\NotImplementedException;
use app\images\compositions\MissingImage;
use tests\TestCase;

class MissingImageTest extends TestCase
{
    public function testGetLayers(): void
    {
        $missingImage = new MissingImage();
        $result = $missingImage->getLayers();
        $this->assertStringContainsString('public/img/ui/missingImg.png', $result[0]);
        $this->assertFileExists($result[0]);
    }

    public function testGetCreatureThrowsExceptions(): void
    {
        $missingImage = new MissingImage();
        $this->expectException(NotImplementedException::class);
        $missingImage->getCreature();
    }

    public function testGetDatabaseThrowsExceptions(): void
    {
        $missingImage = new MissingImage();
        $this->expectException(NotImplementedException::class);
        $missingImage->getDatabase();
    }
}
