<?php
declare(strict_types=1);

namespace app\tests\images\compositions;

use app\game\Creature;
use app\game\creature\CreatureOrigin;
use app\game\creature\Gender;
use app\game\creature\GrowthStage;
use app\game\creature\Loaded;
use app\game\Species;
use app\images\compositions\RidaFrakaso;
use app\images\compositions\Standard;
use PHPUnit\Framework\Attributes\DataProvider;
use tests\stubs\PDO as PDOStub;
use tests\TestCase;

class RidaFrakasoTest extends TestCase
{
    public function testGetCreature(): void
    {
        $pdo = new PDOStub();
        $ridaFrakaso = new RidaFrakaso(
            new Standard(
                $pdo,
                $this->getCreature(
                    $pdo,
                    RidaFrakaso::DARK_ANTLER,
                    GrowthStage::Juvenile
                )
            )
        );
        $this->assertInstanceOf(Creature::class, $ridaFrakaso->getCreature());
    }

    public function testGetDatabase(): void
    {
        $pdo = new PDOStub();
        $ridaFrakaso = new RidaFrakaso(
            new Standard(
                $pdo,
                $this->getCreature(
                    $pdo,
                    RidaFrakaso::DARK_ANTLER,
                    GrowthStage::Juvenile
                )
            )
        );
        $this->assertInstanceOf(PDOStub::class, $ridaFrakaso->getDatabase());
    }

    public function testJuvenile(): void
    {
        $pdo = new PDOStub();
        $ridaFrakaso = new RidaFrakaso(
            new Standard(
                $pdo,
                $this->getCreature(
                    $pdo,
                    RidaFrakaso::DARK_ANTLER,
                    GrowthStage::Juvenile
                )
            )
        );

        $pdo->addData([
            ['part' => 'body'],
            ['part' => 'antlers'],
        ]);
        $pdo->addData([
            [
                'part' => 'body',
                'growth_level' => GrowthStage::Juvenile->value,
                'genetic_code' => 'Body:AA',
                'imgurl_m' => 'foo.png',
                'imgurl_f' => 'bar.png',
            ],
            [
                'part' => 'antlers',
                'growth_level' => GrowthStage::Juvenile->value,
                'genetic_code' => 'Antlers:' . RidaFrakaso::DARK_ANTLER,
                'imgurl_m' => 'transparent.png',
                'imgurl_f' => 'transparent.png',
            ],
        ]);
        $result = $ridaFrakaso->getLayers();

        $this->assertCount(2, $result);
        $this->assertStringContainsString('foo.png', $result[0]);
        $this->assertStringContainsString('transparent.png', $result[1]);
    }

    public function testWithNoAntlers(): void
    {
        $pdo = new PDOStub();
        $ridaFrakaso = new RidaFrakaso(
            new Standard(
                $pdo,
                $this->getCreature(
                    $pdo,
                    'AaBb'
                )
            )
        );

        $pdo->addData([
            ['part' => 'body'],
            ['part' => 'antlers'],
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
                'part' => 'antlers',
                'growth_level' => GrowthStage::Adult->value,
                'genetic_code' => 'Antlers:AaBb',
                'imgurl_m' => 'transparent.png',
                'imgurl_f' => 'transparent.png',
            ],
        ]);
        $result = $ridaFrakaso->getLayers();

        $this->assertCount(4, $result);
        $this->assertNull($result[0]);
        $this->assertStringContainsString('foo.png', $result[1]);
        $this->assertStringContainsString('transparent.png', $result[2]);
        $this->assertNull($result[3]);
    }

    #[DataProvider('getAntlers')]
    public function testAntlers(string $gene, string $expectedNearAntler, string $expectedFarAntler): void
    {
        $pdo = new PDOStub();
        $ridaFrakaso = new RidaFrakaso(
            new Standard(
                $pdo,
                $this->getCreature(
                    $pdo,
                    $gene
                )
            )
        );

        $pdo->addData([
            ['part' => 'body'],
            ['part' => 'antlers'],
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
                'part' => 'antlers',
                'growth_level' => GrowthStage::Adult->value,
                'genetic_code' => 'Antlers:' . $gene,
                'imgurl_m' => 'transparent.png',
                'imgurl_f' => 'transparent.png',
            ],
        ]);
        $result = $ridaFrakaso->getLayers();

        $this->assertCount(4, $result);
        $this->assertStringContainsString($expectedFarAntler, $result[0]);
        $this->assertStringContainsString('foo.png', $result[1]);
        $this->assertStringContainsString('transparent.png', $result[2]);
        $this->assertStringContainsString($expectedNearAntler, $result[3]);
    }

    public static function getAntlers(): array
    {
        $out = [];
        foreach (RidaFrakaso::$directory as $gene => $antlers) {
            $out[] = [$gene, $antlers[RidaFrakaso::POSITION_NEAR], $antlers[RidaFrakaso::POSITION_FAR]];
        }
        return $out;
    }

    private function getCreature(
        PDOStub     $pdo,
        string      $antlerGene,
        GrowthStage $growthStage = GrowthStage::Adult
    ): Creature
    {
        $pdo->addData(
            [
                [
                    'speciesId' => Species::RidaFrakaso->value,
                    'flag' => 0,
                    'name' => '',
                    'growthStageId' => $growthStage->value,
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
                    'genes' => 'Body:AA,Antlers:' . $antlerGene,
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
