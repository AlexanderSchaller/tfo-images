<?php
declare(strict_types=1);

namespace images\compositions;

use app\game\Creature;
use app\game\creature\CreatureOrigin;
use app\game\creature\Gender;
use app\game\creature\GrowthStage;
use app\game\creature\Loaded;
use app\game\Species;
use app\images\compositions\EkvinoskaKavigo;
use app\images\compositions\Standard;
use tests\stubs\PDO as PDOStub;
use tests\TestCase;

class EkvinoskaKavigoTest extends TestCase
{
    public function testGetLayersWithCarrot()
    {
        $pdo = new PDOStub();
        $standard = new Standard($pdo, $this->getCreature($pdo, true));

        $creature = new EkvinoskaKavigo($standard);

        $pdo->addData([
            ['part' => 'body'],
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
                'part' => 'body',
                'growth_level' => GrowthStage::Adult->value,
                'genetic_code' => 'Body:aa',
                'imgurl_m' => 'baz.png',
                'imgurl_f' => 'baq.png',
            ],
        ]);

        $layers = $creature->getLayers();
        $this->assertCount(2, $layers);
        $this->assertStringContainsString('carrot.png', end($layers));
    }

    public function testGetLayersWithoutCarrot()
    {
        $pdo = new PDOStub();
        $standard = new Standard($pdo, $this->getCreature($pdo, false));

        $creature = new EkvinoskaKavigo($standard);

        $pdo->addData([
            ['part' => 'body'],
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
                'part' => 'body',
                'growth_level' => GrowthStage::Adult->value,
                'genetic_code' => 'Body:aa',
                'imgurl_m' => 'baz.png',
                'imgurl_f' => 'baq.png',
            ],
        ]);

        $this->assertCount(1, $creature->getLayers());
    }

    public function testGetCreature()
    {
        $pdo = new PDOStub();
        $standard = new Standard($pdo, $this->getCreature($pdo, false));

        $capsule = new EkvinoskaKavigo($standard);
        $this->assertInstanceOf(Creature::class, $capsule->getCreature());
    }

    public function testGetDatabase(): void
    {
        $pdo = new PDOStub();
        $standard = new Standard($pdo, $this->getCreature($pdo, false));

        $capsule = new EkvinoskaKavigo($standard);
        $this->assertInstanceOf(PDOStub::class, $capsule->getDatabase());
    }

    private function getCreature(
        PDOStub     $pdo,
        bool        $hasCarrot,
        string      $genes = 'Body:AA',
        GrowthStage $growthStage = GrowthStage::Adult
    ): Creature
    {
        $pdo->addData(
            [
                [
                    'speciesId' => Species::ViraBeko->value,
                    'flag' => (int)$hasCarrot,
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
