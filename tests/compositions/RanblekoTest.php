<?php
declare(strict_types=1);

namespace app\tests\images\compositions;

use app\game\Creature;
use app\game\creature\CreatureOrigin;
use app\game\creature\Gender;
use app\game\creature\GrowthStage;
use app\game\creature\Loaded;
use app\game\Species;
use app\images\compositions\Ranbleko;
use app\images\compositions\Standard;
use tests\stubs\PDO as PDOStub;
use tests\TestCase;

class RanblekoTest extends TestCase
{
    public function testGetDatabase(): void
    {
        $pdo = new PDOStub();
        $standard = new Standard($pdo, $this->getCreature($pdo));

        $capsule = new Ranbleko($standard);
        $this->assertInstanceOf(PDOStub::class, $capsule->getDatabase());
    }

    public function testGetCreature(): void
    {
        $pdo = new PDOStub();
        $standard = new Standard($pdo, $this->getCreature($pdo));

        $capsule = new Ranbleko($standard);
        $this->assertInstanceOf(Creature::class, $capsule->getCreature());
    }
    public function testHasNoTongue(): void
    {
        $pdo = new PDOStub();
        $ranbleko = new Ranbleko(new Standard($pdo, $this->getCreature($pdo)));

        $pdo->addData([
            ['part' => 'body'],
            ['part' => 'tail'],
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
                'part' => 'tail',
                'growth_level' => GrowthStage::Adult->value,
                'genetic_code' => 'Tail:aa',
                'imgurl_m' => 'qux.png',
                'imgurl_f' => 'quux.png',
            ],
        ]);

        $result = $ranbleko->getLayers();
        $this->assertCount(3, $result);
        $this->assertStringContainsString('public/foo.png', $result[0]);
        $this->assertStringContainsString('public/qux.png', $result[1]);
        $this->assertNull($result[2]);
    }

    public function testHasTongueJuvenile(): void
    {
        $pdo = new PDOStub();
        $ranbleko = new Ranbleko(
            new Standard(
                $pdo,
                $this->getCreature(
                    $pdo,
                    Ranbleko::TONGUE_UP,
                    GrowthStage::Juvenile
                )
            )
        );

        $pdo->addData([
            ['part' => 'body'],
            ['part' => 'tail'],
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
                'part' => 'tail',
                'growth_level' => GrowthStage::Juvenile->value,
                'genetic_code' => 'Tail:aa',
                'imgurl_m' => 'qux.png',
                'imgurl_f' => 'quux.png',
            ],
        ]);

        $result = $ranbleko->getLayers();
        $this->assertCount(2, $result);
        $this->assertStringContainsString('public/foo.png', $result[0]);
        $this->assertStringContainsString('public/qux.png', $result[1]);
    }

    public function testHasTongueUp(): void
    {
        $pdo = new PDOStub();
        $ranbleko = new Ranbleko(
            new Standard(
                $pdo,
                $this->getCreature(
                    $pdo,
                    Ranbleko::TONGUE_UP
                )
            )
        );

        $pdo->addData([
            ['part' => 'body'],
            ['part' => 'tail'],
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
                'part' => 'tail',
                'growth_level' => GrowthStage::Adult->value,
                'genetic_code' => 'Tail:aa',
                'imgurl_m' => 'qux.png',
                'imgurl_f' => 'quux.png',
            ],
        ]);

        $result = $ranbleko->getLayers();
        $this->assertCount(3, $result);
        $this->assertStringContainsString('public/foo.png', $result[0]);
        $this->assertStringContainsString('public/qux.png', $result[1]);
        $this->assertStringContainsString('/public/img/bases/ranbleko/s3/blep/1.png', $result[2]);
    }

    public function testHasTongueDown(): void
    {
        $pdo = new PDOStub();
        $ranbleko = new Ranbleko(
            new Standard(
                $pdo,
                $this->getCreature(
                    $pdo,
                    Ranbleko::TONGUE_DOWN
                )
            )
        );

        $pdo->addData([
            ['part' => 'body'],
            ['part' => 'tail'],
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
                'part' => 'tail',
                'growth_level' => GrowthStage::Adult->value,
                'genetic_code' => 'Tail:aa',
                'imgurl_m' => 'qux.png',
                'imgurl_f' => 'quux.png',
            ],
        ]);

        $result = $ranbleko->getLayers();
        $this->assertCount(3, $result);
        $this->assertStringContainsString('public/foo.png', $result[0]);
        $this->assertStringContainsString('public/qux.png', $result[1]);
        $this->assertStringContainsString('/public/img/bases/ranbleko/s3/blep/2.png', $result[2]);
    }

    private function getCreature(
        PDOStub     $pdo,
        int         $hasTongue = 0,
        GrowthStage $growthStage = GrowthStage::Adult
    ): Creature
    {
        $pdo->addData(
            [
                [
                    'speciesId' => Species::Ranbleko->value,
                    'flag' => $hasTongue,
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
                    'genes' => 'Body:AA,Tail:aa',
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
