<?php
declare(strict_types=1);

namespace app\tests\images;

use app\game\creature\CreatureOrigin;
use app\game\creature\Gender;
use app\game\creature\GrowthStage;
use app\game\creature\Loaded;
use app\game\Species;
use app\images\compositions\Standard;
use app\images\Image;
use tests\stubs\PDO as PDOStub;
use tests\stubs\Renderer;
use tests\stubs\SimpleImage;
use tests\TestCase;

class ImageTest extends TestCase
{
    public function testRenderWithTransparentLayer(): void
    {
        $imageEngine = new SimpleImage();
        $pdo = new PDOStub();
        $creature = $this->getCreature($pdo);
        $pdo->addData([
            ['part' => 'body'],
            ['part' => 'head'],
        ]);
        $pdo->addData([
            [
                'part' => 'body',
                'growth_level' => GrowthStage::Adult->value,
                'genetic_code' => 'Body:AA',
                'imgurl_m' => null,
                'imgurl_f' => null,
            ],
            [
                'part' => 'head',
                'growth_level' => GrowthStage::Adult->value,
                'genetic_code' => 'Head:AABBCC',
                'imgurl_m' => 'qux.png',
                'imgurl_f' => 'quux.png',
            ],
        ]);
        $image = new Image(
            new Standard(
                $pdo,
                $creature
            ),
            $imageEngine
        );
        $image->render(new Renderer());

        $this->assertCount(1, $imageEngine->getLayers());
    }

    public function testRender(): void
    {
        $imageEngine = new SimpleImage();
        $pdo = new PDOStub();
        $creature = $this->getCreature($pdo);
        $pdo->addData([
            ['part' => 'body'],
            ['part' => 'head'],
        ]);
        $pdo->addData([
            [
                'part' => 'body',
                'growth_level' => GrowthStage::Adult->value,
                'genetic_code' => 'Body:AA',
                'imgurl_m' => 'foo.png',
                'imgurl_f' => 'bar.png',
            ],
            [
                'part' => 'head',
                'growth_level' => GrowthStage::Adult->value,
                'genetic_code' => 'Head:AABBCC',
                'imgurl_m' => 'qux.png',
                'imgurl_f' => 'quux.png',
            ],
        ]);
        $image = new Image(
            new Standard(
                $pdo,
                $creature
            ),
            $imageEngine
        );
        $image->render(new Renderer());

        $this->assertCount(2, $imageEngine->getLayers());
    }

    private function getCreature(PDOStub $pdo): Loaded
    {
        $pdo->addData(
            [
                [
                    'speciesId' => Species::ViraBeko->value,
                    'flag' => 0,
                    'name' => '',
                    'growthStageId' => GrowthStage::Adult->value,
                    'userId' => 1,
                    'previousOwnerId' => 1,
                    'originalOwnerId' => 1,
                    'obtainedTime' => 2,
                    'emergeTime' => 3,
                    'matureTime' => 4,
                    'mother' => null,
                    'father' => null,
                    'appearance' => 5,
                    'happiness' => 6,
                    'hardiness' => 7,
                    'views' => 8,
                    'gender' => Gender::Male->value,
                    'genes' => 'Body:AA,Head:AABBCC',
                    'tab' => 9,
                    'gottenFrom' => CreatureOrigin::Cupboard->value,
                ],
            ]
        );

        return new Loaded(
            'abcde',
            $pdo
        );
    }
}
