<?php
declare(strict_types=1);

namespace app\tests\images\compositions;

use app\game\Creature;
use app\game\creature\CreatureOrigin;
use app\game\creature\Gender;
use app\game\creature\GrowthStage;
use app\game\creature\Loaded;
use app\game\Species;
use app\images\compositions\DentegaSalto;
use app\images\compositions\Standard;
use tests\stubs\PDO as PDOStub;
use tests\TestCase;

class DentegaSaltoTest extends TestCase
{
    public function testGetCreature(): void
    {
        $pdo = new PDOStub();
        $dentegaSalto = new DentegaSalto(
            new Standard(
                $pdo,
                $this->getCreature(
                    $pdo,
                    GrowthStage::Adult,
                    'AA',
                    'AaBbCc'
                )
            )
        );

        $this->assertInstanceOf(Creature::class, $dentegaSalto->getCreature());
    }

    public function testGetDatabase(): void
    {
        $pdo = new PDOStub();
        $dentegaSalto = new DentegaSalto(
            new Standard(
                $pdo,
                $this->getCreature(
                    $pdo,
                    GrowthStage::Adult,
                    'AA',
                    'AaBbCc'
                )
            )
        );

        $this->assertInstanceOf(PDOStub::class, $dentegaSalto->getDatabase());
    }

    public function testGetLayersComboAAdult(): void
    {
        $pdo = new PDOStub();
        $dentegaSalto = new DentegaSalto(
            new Standard(
                $pdo,
                $this->getCreature(
                    $pdo,
                    GrowthStage::Adult,
                    'AA',
                    'AaBbCc'
                )
            )
        );

        $pdo->addData([
            ['part' => 'body'],
            ['part' => 'ears'],
        ]);
        $pdo->addData([
            [
                'part' => 'body',
                'growth_level' => GrowthStage::Adult->value,
                'genetic_code' => 'Body:AaBbCc',
                'imgurl_m' => 'foo.png',
                'imgurl_f' => 'bar.png',
            ],
            [
                'part' => 'ears',
                'growth_level' => GrowthStage::Adult->value,
                'genetic_code' => 'Ears:AA',
                'imgurl_m' => 'corge.png',
                'imgurl_f' => 'grault.png',
            ],
        ]);

        $result = $dentegaSalto->getLayers();
        $this->assertCount(3, $result);
        $this->assertStringContainsString('/public/img/bases/dentegasalto/s3/ears/lop/14.png', $result[1]);
    }

    public function testGetLayersComboAJuvenile(): void
    {
        $pdo = new PDOStub();
        $dentegaSalto = new DentegaSalto(
            new Standard(
                $pdo,
                $this->getCreature(
                    $pdo,
                    GrowthStage::Juvenile,
                    'AA',
                    'AaBbCc'
                )
            )
        );

        $pdo->addData([
            ['part' => 'body'],
            ['part' => 'ears'],
        ]);
        $pdo->addData([
            [
                'part' => 'body',
                'growth_level' => GrowthStage::Juvenile->value,
                'genetic_code' => 'Body:AaBbCc',
                'imgurl_m' => 'foo.png',
                'imgurl_f' => 'bar.png',
            ],
            [
                'part' => 'ears',
                'growth_level' => GrowthStage::Juvenile->value,
                'genetic_code' => 'Ears:AA',
                'imgurl_m' => 'corge.png',
                'imgurl_f' => 'grault.png',
            ],
        ]);

        $result = $dentegaSalto->getLayers();
        $this->assertCount(3, $result);
        $this->assertStringContainsString('/public/img/bases/dentegasalto/s2/ears/lop/14.png', $result[1]);
    }

    public function testGetLayersComboBAdult(): void
    {
        $pdo = new PDOStub();
        $dentegaSalto = new DentegaSalto(
            new Standard(
                $pdo,
                $this->getCreature(
                    $pdo,
                    GrowthStage::Adult,
                    'Aa',
                    'aabbcc'
                )
            )
        );

        $pdo->addData([
            ['part' => 'body'],
            ['part' => 'ears'],
        ]);
        $pdo->addData([
            [
                'part' => 'body',
                'growth_level' => GrowthStage::Adult->value,
                'genetic_code' => 'Body:aabbcc',
                'imgurl_m' => 'foo.png',
                'imgurl_f' => 'bar.png',
            ],
            [
                'part' => 'ears',
                'growth_level' => GrowthStage::Adult->value,
                'genetic_code' => 'Ears:Aa',
                'imgurl_m' => 'corge.png',
                'imgurl_f' => 'grault.png',
            ],
        ]);

        $result = $dentegaSalto->getLayers();
        $this->assertCount(3, $result);
        $this->assertStringContainsString('/public/img/bases/dentegasalto/s3/ears/regular/27.png', $result[1]);
    }

    public function testGetLayersComboBJuvenile(): void
    {
        $pdo = new PDOStub();
        $dentegaSalto = new DentegaSalto(
            new Standard(
                $pdo,
                $this->getCreature(
                    $pdo,
                    GrowthStage::Juvenile,
                    'Aa',
                    'aabbcc'
                )
            )
        );

        $pdo->addData([
            ['part' => 'body'],
            ['part' => 'ears'],
        ]);
        $pdo->addData([
            [
                'part' => 'body',
                'growth_level' => GrowthStage::Juvenile->value,
                'genetic_code' => 'Body:aabbcc',
                'imgurl_m' => 'foo.png',
                'imgurl_f' => 'bar.png',
            ],
            [
                'part' => 'ears',
                'growth_level' => GrowthStage::Juvenile->value,
                'genetic_code' => 'Ears:Aa',
                'imgurl_m' => 'corge.png',
                'imgurl_f' => 'grault.png',
            ],
        ]);
        $result = $dentegaSalto->getLayers();
        $this->assertCount(3, $result);
        $this->assertStringContainsString('/public/img/bases/dentegasalto/s2/ears/regular/27.png', $result[1]);
    }

    private function getCreature(
        PDOStub     $pdo,
        GrowthStage $growthStage,
        string      $earGene,
        string      $bodyGene,
    ): Creature
    {
        $pdo->addData(
            [
                [
                    'speciesId' => Species::DentegaSalto->value,
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
                    'genes' => 'Body:' . $bodyGene . ',Ears:' . $earGene,
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
