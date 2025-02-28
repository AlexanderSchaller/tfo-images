<?php
declare(strict_types=1);

namespace images\compositions;

use app\game\Creature;
use app\game\creature\CreatureOrigin;
use app\game\creature\Gender;
use app\game\creature\GrowthStage;
use app\game\creature\Loaded;
use app\game\Species;
use app\images\compositions\GlaciaAlsalto;
use app\images\compositions\Standard;
use tests\stubs\PDO as PDOStub;
use tests\TestCase;

class GlaciaAlsaltoTest extends TestCase
{
    public function testGoldCoatJuvenile(): void
    {
        $pdo = new PDOStub();
        $glaciaAlsalto = new GlaciaAlsalto(
            new Standard(
                $pdo,
                $this->getCreature(
                    $pdo,
                    GrowthStage::Juvenile,
                    'AAbbCC',
                    'AA'
                )
            )
        );

        $pdo->addData([
            ['part' => 'body'],
            ['part' => 'tails'],
        ]);
        $pdo->addData([
            [
                'part' => 'body',
                'growth_level' => GrowthStage::Juvenile->value,
                'genetic_code' => 'Body:AAbbCC',
                'imgurl_m' => 'foo.png',
                'imgurl_f' => 'bar.png',
            ],
            [
                'part' => 'tails',
                'growth_level' => GrowthStage::Juvenile->value,
                'genetic_code' => 'Tails:AA',
                'imgurl_m' => 'corge.png',
                'imgurl_f' => 'grault.png',
            ],
        ]);
        $result = $glaciaAlsalto->getLayers();
        $this->assertCount(3, $result);
        $this->assertStringContainsString('/public/img/bases/alsalto/s2/tails/AA_9_1.png', $result[0]);
    }

    public function testGoldCoatAdult(): void
    {
        $pdo = new PDOStub();
        $glaciaAlsalto = new GlaciaAlsalto(
            new Standard(
                $pdo,
                $this->getCreature(
                    $pdo,
                    GrowthStage::Adult,
                    'AAbbcc',
                    'aa'
                )
            )
        );

        $pdo->addData([
            ['part' => 'body'],
            ['part' => 'tails'],
        ]);
        $pdo->addData([
            [
                'part' => 'body',
                'growth_level' => GrowthStage::Adult->value,
                'genetic_code' => 'Body:AAbbcc',
                'imgurl_m' => 'foo.png',
                'imgurl_f' => 'bar.png',
            ],
            [
                'part' => 'tails',
                'growth_level' => GrowthStage::Adult->value,
                'genetic_code' => 'Tails:aa',
                'imgurl_m' => 'corge.png',
                'imgurl_f' => 'grault.png',
            ],
        ]);
        $result = $glaciaAlsalto->getLayers();
        $this->assertCount(3, $result);
        $this->assertStringContainsString('/public/img/bases/alsalto/s3/tails/AA_3_1.png', $result[0]);
    }

    public function testBlueCoatJuvenile(): void
    {
        $pdo = new PDOStub();
        $glaciaAlsalto = new GlaciaAlsalto(
            new Standard(
                $pdo,
                $this->getCreature(
                    $pdo,
                    GrowthStage::Juvenile,
                    'AaBbcc',
                    'Aa'
                )
            )
        );

        $pdo->addData([
            ['part' => 'body'],
            ['part' => 'tails'],
        ]);
        $pdo->addData([
            [
                'part' => 'body',
                'growth_level' => GrowthStage::Juvenile->value,
                'genetic_code' => 'Body:AaBbcc',
                'imgurl_m' => 'foo.png',
                'imgurl_f' => 'bar.png',
            ],
            [
                'part' => 'tails',
                'growth_level' => GrowthStage::Juvenile->value,
                'genetic_code' => 'Tails:Aa',
                'imgurl_m' => 'corge.png',
                'imgurl_f' => 'grault.png',
            ],
        ]);
        $result = $glaciaAlsalto->getLayers();
        $this->assertCount(3, $result);
        $this->assertStringContainsString('/public/img/bases/alsalto/s2/tails/Aa_6.png', $result[0]);
    }

    public function testBlueCoatAdult(): void
    {
        $pdo = new PDOStub();
        $glaciaAlsalto = new GlaciaAlsalto(
            new Standard(
                $pdo,
                $this->getCreature(
                    $pdo,
                    GrowthStage::Adult,
                    'AabbCc',
                    'aa'
                )
            )
        );

        $pdo->addData([
            ['part' => 'body'],
            ['part' => 'tails'],
        ]);
        $pdo->addData([
            [
                'part' => 'body',
                'growth_level' => GrowthStage::Adult->value,
                'genetic_code' => 'Body:AabbCc',
                'imgurl_m' => 'foo.png',
                'imgurl_f' => 'bar.png',
            ],
            [
                'part' => 'tails',
                'growth_level' => GrowthStage::Adult->value,
                'genetic_code' => 'Tails:aa',
                'imgurl_m' => 'corge.png',
                'imgurl_f' => 'grault.png',
            ],
        ]);

        $result = $glaciaAlsalto->getLayers();
        $this->assertCount(3, $result);
        $this->assertStringContainsString('/public/img/bases/alsalto/s3/tails/Aa_3.png', $result[0]);
    }

    public function testWhiteCoatJuvenile(): void
    {
        $pdo = new PDOStub();
        $glaciaAlsalto = new GlaciaAlsalto(
            new Standard(
                $pdo,
                $this->getCreature(
                    $pdo,
                    GrowthStage::Juvenile,
                    'aabbCC',
                    'Aa'
                )
            )
        );

        $pdo->addData([
            ['part' => 'body'],
            ['part' => 'tails'],
        ]);
        $pdo->addData([
            [
                'part' => 'body',
                'growth_level' => GrowthStage::Juvenile->value,
                'genetic_code' => 'Body:aabbCC',
                'imgurl_m' => 'foo.png',
                'imgurl_f' => 'bar.png',
            ],
            [
                'part' => 'tails',
                'growth_level' => GrowthStage::Juvenile->value,
                'genetic_code' => 'Tails:Aa',
                'imgurl_m' => 'corge.png',
                'imgurl_f' => 'grault.png',
            ],
        ]);
        $result = $glaciaAlsalto->getLayers();
        $this->assertCount(3, $result);
        $this->assertStringContainsString('/public/img/bases/alsalto/s2/tails/aa_6_2.png', $result[0]);
    }

    public function testWhiteCoatAdult(): void
    {
        $pdo = new PDOStub();
        $glaciaAlsalto = new GlaciaAlsalto(
            new Standard(
                $pdo,
                $this->getCreature(
                    $pdo,
                    GrowthStage::Adult,
                    'aabbcc',
                    'AA'
                )
            )
        );

        $pdo->addData([
            ['part' => 'body'],
            ['part' => 'tails'],
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
                'part' => 'tails',
                'growth_level' => GrowthStage::Adult->value,
                'genetic_code' => 'Tails:AA',
                'imgurl_m' => 'corge.png',
                'imgurl_f' => 'grault.png',
            ],
        ]);
        $result = $glaciaAlsalto->getLayers();
        $this->assertCount(3, $result);
        $this->assertStringContainsString('/public/img/bases/alsalto/s3/tails/aa_9_2.png', $result[0]);
    }

    public function testGetCreature(): void
    {
        $pdo = new PDOStub();
        $glaciaAlsalto = new GlaciaAlsalto(
            new Standard(
                $pdo,
                $this->getCreature(
                    $pdo,
                    GrowthStage::Adult,
                    'AABBCC',
                    'AA'
                )
            )
        );

        $this->assertInstanceOf(Creature::class, $glaciaAlsalto->getCreature());
    }

    public function testGetDatabase(): void
    {
        $pdo = new PDOStub();
        $glaciaAlsalto = new GlaciaAlsalto(
            new Standard(
                $pdo,
                $this->getCreature(
                    $pdo,
                    GrowthStage::Adult,
                    'AABBCC',
                    'AA'
                )
            )
        );

        $this->assertInstanceOf(PDOStub::class, $glaciaAlsalto->getDatabase());
    }

    private function getCreature(PDOStub $pdo, GrowthStage $growthStage, string $bodyGene, string $tailsGene): Loaded
    {
        $pdo->addData(
            [
                [
                    'speciesId' => Species::GlaciaAlsalto->value,
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
                    'genes' => 'Body:' . $bodyGene . ',Tails:' . $tailsGene,
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
