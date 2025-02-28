<?php
declare(strict_types=1);

namespace app\tests\images\compositions;

use app\game\Creature;
use app\game\creature\CreatureOrigin;
use app\game\creature\Gender;
use app\game\creature\GrowthStage;
use app\game\creature\Loaded;
use app\game\Species;
use app\images\compositions\MuskaFelo;
use app\images\compositions\Standard;
use tests\stubs\PDO as PDOStub;
use tests\TestCase;

class MuskaFeloTest extends TestCase
{
    public function testGetDatabase(): void
    {
        $pdo = new PDOStub();
        $muskaFelo = new MuskaFelo(
            new Standard(
                $pdo,
                $this->getCreature(
                    $pdo,
                    'AaBb',
                    'Aa',
                    GrowthStage::Juvenile
                )
            )
        );

        $this->assertInstanceOf(PDOStub::class, $muskaFelo->getDatabase());
    }

    public function testGetCreature(): void
    {
        $pdo = new PDOStub();
        $muskaFelo = new MuskaFelo(
            new Standard(
                $pdo,
                $this->getCreature(
                    $pdo,
                    'AaBb',
                    'Aa',
                    GrowthStage::Juvenile
                )
            )
        );

        $this->assertInstanceOf(Creature::class, $muskaFelo->getCreature());
    }

    public function testGetLayersJuvenile(): void
    {
        $pdo = new PDOStub();
        $muskaFelo = new MuskaFelo(
            new Standard(
                $pdo,
                $this->getCreature(
                    $pdo,
                    'AaBb',
                    'Aa',
                    GrowthStage::Juvenile
                )
            )
        );

        $pdo->addData([
            ['part' => 'body'],
            ['part' => 'length'],
        ]);
        $pdo->addData([
            [
                'part' => 'body',
                'growth_level' => GrowthStage::Juvenile->value,
                'genetic_code' => 'Body:AaBb',
                'imgurl_m' => 'foo.png',
                'imgurl_f' => 'bar.png',
            ],
            [
                'part' => 'length',
                'growth_level' => GrowthStage::Juvenile->value,
                'genetic_code' => 'Length:Aa',
                'imgurl_m' => 'corge.png',
                'imgurl_f' => 'grault.png',
            ],
        ]);

        $this->assertCount(2, $muskaFelo->getLayers());
    }

    public function testGetLayersAdultComboA(): void
    {
        $pdo = new PDOStub();
        $muskaFelo = new MuskaFelo(
            new Standard(
                $pdo,
                $this->getCreature(
                    $pdo,
                    'aabb',
                    'aa',
                    GrowthStage::Adult
                )
            )
        );

        $pdo->addData([
            ['part' => 'body'],
            ['part' => 'length'],
        ]);
        $pdo->addData([
            [
                'part' => 'body',
                'growth_level' => GrowthStage::Adult->value,
                'genetic_code' => 'Body:aabb',
                'imgurl_m' => 'foo.png',
                'imgurl_f' => 'bar.png',
            ],
            [
                'part' => 'length',
                'growth_level' => GrowthStage::Adult->value,
                'genetic_code' => 'Length:aa',
                'imgurl_m' => 'corge.png',
                'imgurl_f' => 'grault.png',
            ],
        ]);

        $result = $muskaFelo->getLayers();
        $this->assertCount(3, $result);
        $this->assertStringContainsString('/public/img/bases/muskafelo/s3/earssm/2.png', $result[2]);
    }

    public function testGetLayersAdultComboB(): void
    {
        $pdo = new PDOStub();
        $muskaFelo = new MuskaFelo(
            new Standard(
                $pdo,
                $this->getCreature(
                    $pdo,
                    'AaBb',
                    'AA',
                    GrowthStage::Adult
                )
            )
        );

        $pdo->addData([
            ['part' => 'body'],
            ['part' => 'length'],
        ]);
        $pdo->addData([
            [
                'part' => 'body',
                'growth_level' => GrowthStage::Adult->value,
                'genetic_code' => 'Body:AaBb',
                'imgurl_m' => 'foo.png',
                'imgurl_f' => 'bar.png',
            ],
            [
                'part' => 'length',
                'growth_level' => GrowthStage::Adult->value,
                'genetic_code' => 'Length:AA',
                'imgurl_m' => 'corge.png',
                'imgurl_f' => 'grault.png',
            ],
        ]);

        $result = $muskaFelo->getLayers();
        $this->assertCount(3, $result);
        $this->assertStringContainsString('/public/img/bases/muskafelo/s3/earslg/9.png', $result[2]);
    }


    private function getCreature(PDOStub $pdo, string $bodyGene, string $earGene, GrowthStage $growthStage): Loaded
    {
        $pdo->addData(
            [
                [
                    'speciesId' => Species::MuskaFelo->value,
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
                    'genes' => 'Body:' . $bodyGene . ',Length:' . $earGene,
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
