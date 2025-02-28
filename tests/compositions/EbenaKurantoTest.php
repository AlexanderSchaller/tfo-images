<?php
declare(strict_types=1);

namespace app\tests\images\compositions;

use app\game\Creature;
use app\game\creature\CreatureOrigin;
use app\game\creature\Gender;
use app\game\creature\GrowthStage;
use app\game\creature\Loaded;
use app\game\Species;
use app\images\compositions\EbenaKuranto;
use app\images\compositions\Standard;
use tests\stubs\PDO as PDOStub;
use tests\TestCase;

class EbenaKurantoTest extends TestCase
{
    public function testGetDatabase(): void
    {
        $pdo = new PDOStub();
        $standard = new Standard($pdo, $this->getCreature($pdo));

        $capsule = new EbenaKuranto($standard);
        $this->assertInstanceOf(PDOStub::class, $capsule->getDatabase());
    }

    public function testGetCreature(): void
    {
        $pdo = new PDOStub();
        $standard = new Standard($pdo, $this->getCreature($pdo));

        $capsule = new EbenaKuranto($standard);
        $this->assertInstanceOf(Creature::class, $capsule->getCreature());
    }

    public function testGetOnJuvenile(): void
    {
        $pdo = new PDOStub();
        $ebena = new EbenaKuranto(
            new Standard(
                $pdo,
                $this->getCreature(
                    $pdo,
                    'Body:AA,Wings:' . EbenaKuranto::WING_PEGASUS,
                    GrowthStage::Juvenile
                )
            )
        );
        $pdo->addData([
            ['part' => 'body'],
            ['part' => 'wings'],
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
                'part' => 'wings',
                'growth_level' => GrowthStage::Juvenile->value,
                'genetic_code' => 'Wings:' . EbenaKuranto::WING_PEGASUS,
                'imgurl_m' => 'qux.png',
                'imgurl_f' => 'quux.png',
            ],
        ]);

        $result = $ebena->getLayers();
        $this->assertCount(2, $result);
        $this->assertStringContainsString('public/foo.png', $result[0]);
        $this->assertStringContainsString('public/qux.png', $result[1]);
    }

    public function testGetOnAdultWithoutWings(): void
    {
        $pdo = new PDOStub();
        $ebena = new EbenaKuranto(
            new Standard(
                $pdo,
                $this->getCreature($pdo)
            )
        );
        $pdo->addData([
            ['part' => 'body'],
            ['part' => 'wings'],
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
                'part' => 'wings',
                'growth_level' => GrowthStage::Adult->value,
                'genetic_code' => 'Wings:AABBCC',
                'imgurl_m' => 'qux.png',
                'imgurl_f' => 'quux.png',
            ],
        ]);

        $result = $ebena->getLayers();
        $this->assertCount(2, $result);
        $this->assertStringContainsString('public/foo.png', $result[0]);
        $this->assertStringContainsString('public/qux.png', $result[1]);
    }

    public function testGetAdultPegasus(): void
    {
        $pdo = new PDOStub();
        $ebena = new EbenaKuranto(
            new Standard(
                $pdo,
                $this->getCreature(
                    $pdo,
                    'Body:AA,Wings:' . EbenaKuranto::WING_PEGASUS,
                )
            )
        );
        $pdo->addData([
            ['part' => 'body'],
            ['part' => 'wings'],
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
                'part' => 'wings',
                'growth_level' => GrowthStage::Adult->value,
                'genetic_code' => 'Wings:' . EbenaKuranto::WING_PEGASUS,
                'imgurl_m' => 'qux.png',
                'imgurl_f' => 'quux.png',
            ],
        ]);

        $result = $ebena->getLayers();

        $this->assertCount(3, $result);
        $this->assertStringContainsString(
            '/public/img/bases/ebenakuranto/s3/wings/pegasus-farwing.png',
            $result[0]
        );
        $this->assertStringContainsString('public/foo.png', $result[1]);
        $this->assertStringContainsString('public/qux.png', $result[2]);
    }

    public function testGetAdultJerseyDevil(): void
    {
        $pdo = new PDOStub();
        $ebena = new EbenaKuranto(
            new Standard(
                $pdo,
                $this->getCreature(
                    $pdo,
                    'Body:AA,Wings:' . EbenaKuranto::WING_JERSEY_DEVIL,
                )
            )
        );
        $pdo->addData([
            ['part' => 'body'],
            ['part' => 'wings'],
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
                'part' => 'wings',
                'growth_level' => GrowthStage::Adult->value,
                'genetic_code' => 'Wings:' . EbenaKuranto::WING_JERSEY_DEVIL,
                'imgurl_m' => 'qux.png',
                'imgurl_f' => 'quux.png',
            ],
        ]);

        $result = $ebena->getLayers();

        $this->assertCount(3, $result);
        $this->assertStringContainsString(
            '/public/img/bases/ebenakuranto/s3/wings/jerseydevil-farwing.png',
            $result[0]
        );
        $this->assertStringContainsString('public/foo.png', $result[1]);
        $this->assertStringContainsString('public/qux.png', $result[2]);
    }

    private function getCreature(
        PDOStub     $pdo,
        string      $genes = 'Body:AA,Wings:AABBCC', // no wings
        GrowthStage $growthStage = GrowthStage::Adult
    ): Creature
    {
        $pdo->addData(
            [
                [
                    'speciesId' => Species::EbenaKuranto->value,
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
                    'genes' => $genes,
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
