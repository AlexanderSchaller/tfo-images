<?php
declare(strict_types=1);

namespace app\tests\images\compositions;

use app\game\Creature;
use app\game\creature\CreatureOrigin;
use app\game\creature\Gender;
use app\game\creature\GrowthStage;
use app\game\creature\Loaded;
use app\game\Species;
use app\images\compositions\Standard;
use app\images\compositions\ViraBeko;
use tests\stubs\PDO as PDOStub;
use tests\TestCase;

class ViraBekoTest extends TestCase
{
    public function testGetDatabase(): void
    {
        $pdo = new PDOStub();
        $standard = new Standard($pdo, $this->getCreature($pdo, false));

        $capsule = new ViraBeko($standard);
        $this->assertInstanceOf(PDOStub::class, $capsule->getDatabase());
    }

    public function testGetCreature(): void
    {
        $pdo = new PDOStub();
        $standard = new Standard($pdo, $this->getCreature($pdo, false));

        $capsule = new ViraBeko($standard);
        $this->assertInstanceOf(Creature::class, $capsule->getCreature());
    }

    public function testWithoutCimo(): void
    {
        $pdo = new PDOStub();
        $creature = $this->getCreature($pdo, false);

        $viraBeko = new ViraBeko(new Standard($pdo, $creature));

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
                'part' => 'body',
                'growth_level' => GrowthStage::Adult->value,
                'genetic_code' => 'Body:aa',
                'imgurl_m' => 'baz.png',
                'imgurl_f' => 'baq.png',
            ],
            [
                'part' => 'head',
                'growth_level' => GrowthStage::Adult->value,
                'genetic_code' => 'Head:AABBCC',
                'imgurl_m' => 'qux.png',
                'imgurl_f' => 'quux.png',
            ],
            [
                'part' => 'head',
                'growth_level' => GrowthStage::Adult->value,
                'genetic_code' => 'Head:aabbcc',
                'imgurl_m' => 'corge.png',
                'imgurl_f' => 'grault.png',
            ],
        ]);

        $result = $viraBeko->getLayers();

        $this->assertCount(2, $result);
        $this->assertStringContainsString('public/foo.png', $result[0]);
        $this->assertStringContainsString('public/qux.png', $result[1]);
    }

    public function testWithCimoRightFacingHead(): void
    {
        $pdo = new PDOStub();
        $creature = $this->getCreature($pdo, true);

        $viraBeko = new ViraBeko(new Standard($pdo, $creature));

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
                'part' => 'body',
                'growth_level' => GrowthStage::Adult->value,
                'genetic_code' => 'Body:aa',
                'imgurl_m' => 'baz.png',
                'imgurl_f' => 'baq.png',
            ],
            [
                'part' => 'head',
                'growth_level' => GrowthStage::Adult->value,
                'genetic_code' => 'Head:AABBCC',
                'imgurl_m' => 'qux.png',
                'imgurl_f' => 'quux.png',
            ],
            [
                'part' => 'head',
                'growth_level' => GrowthStage::Adult->value,
                'genetic_code' => 'Head:aabbcc',
                'imgurl_m' => 'corge.png',
                'imgurl_f' => 'grault.png',
            ],
        ]);

        $result = $viraBeko->getLayers();

        $this->assertCount(3, $result);
        $this->assertStringContainsString('public/foo.png', $result[0]);
        $this->assertStringContainsString('public/qux.png', $result[1]);
        $this->assertStringContainsString('public/img/bases/turkey/s3/cimo/2.png', $result[2]);
    }

    public function testWithCimoLeftFacingHead(): void
    {
        $pdo = new PDOStub();
        $creature = $this->getCreature($pdo, true, 'Body:AA,Head:aabbcc');

        $viraBeko = new ViraBeko(new Standard($pdo, $creature));

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
                'part' => 'body',
                'growth_level' => GrowthStage::Adult->value,
                'genetic_code' => 'Body:aa',
                'imgurl_m' => 'baz.png',
                'imgurl_f' => 'baq.png',
            ],
            [
                'part' => 'head',
                'growth_level' => GrowthStage::Adult->value,
                'genetic_code' => 'Head:AABBCC',
                'imgurl_m' => 'qux.png',
                'imgurl_f' => 'quux.png',
            ],
            [
                'part' => 'head',
                'growth_level' => GrowthStage::Adult->value,
                'genetic_code' => 'Head:aabbcc',
                'imgurl_m' => 'corge.png',
                'imgurl_f' => 'grault.png',
            ],
        ]);

        $result = $viraBeko->getLayers();

        $this->assertCount(3, $result);
        $this->assertStringContainsString('public/foo.png', $result[0]);
        $this->assertStringContainsString('public/corge.png', $result[1]);
        $this->assertStringContainsString('public/img/bases/turkey/s3/cimo/1.png', $result[2]);
    }

    public function testGetJuvenileWithCimoDoesNotShow(): void
    {
        $pdo = new PDOStub();
        $creature = $this->getCreature($pdo, true, 'Body:AA,Head:aabbcc', GrowthStage::Juvenile);

        $viraBeko = new ViraBeko(new Standard($pdo, $creature));

        $pdo->addData([
            ['part' => 'body'],
            ['part' => 'head'],
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
                'part' => 'body',
                'growth_level' => GrowthStage::Juvenile->value,
                'genetic_code' => 'Body:aa',
                'imgurl_m' => 'baz.png',
                'imgurl_f' => 'baq.png',
            ],
            [
                'part' => 'head',
                'growth_level' => GrowthStage::Juvenile->value,
                'genetic_code' => 'Head:AABBCC',
                'imgurl_m' => 'qux.png',
                'imgurl_f' => 'quux.png',
            ],
            [
                'part' => 'head',
                'growth_level' => GrowthStage::Juvenile->value,
                'genetic_code' => 'Head:aabbcc',
                'imgurl_m' => 'corge.png',
                'imgurl_f' => 'grault.png',
            ],
        ]);

        $result = $viraBeko->getLayers();

        $this->assertCount(2, $result);
        $this->assertStringContainsString('public/foo.png', $result[0]);
        $this->assertStringContainsString('public/corge.png', $result[1]);
    }

    private function getCreature(
        PDOStub     $pdo,
        bool        $hasCimo,
        string      $genes = 'Body:AA,Head:AABBCC', // right facing head,
        GrowthStage $growthStage = GrowthStage::Adult
    ): Creature
    {
        $pdo->addData(
            [
                [
                    'speciesId' => Species::ViraBeko->value,
                    'flag' => (int)$hasCimo,
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
