<?php
declare(strict_types=1);

namespace app\tests\images\compositions;

use app\game\Creature;
use app\game\creature\CreatureOrigin;
use app\game\creature\Gender;
use app\game\creature\GrowthStage;
use app\game\creature\Loaded;
use app\game\Species;
use app\images\compositions\NebulaGlisanto;
use app\images\compositions\Standard;
use PHPUnit\Framework\Attributes\DataProvider;
use tests\stubs\PDO as PDOStub;
use tests\TestCase;

class NebulaGlisantoTest extends TestCase
{
    public function testGetDatabase(): void
    {
        $pdo = new PDOStub();
        $standard = new Standard($pdo, $this->getCreature($pdo));

        $capsule = new NebulaGlisanto($standard);
        $this->assertInstanceOf(PDOStub::class, $capsule->getDatabase());
    }

    public function testGetCreature(): void
    {
        $pdo = new PDOStub();
        $standard = new Standard($pdo, $this->getCreature($pdo));

        $capsule = new NebulaGlisanto($standard);
        $this->assertInstanceOf(Creature::class, $capsule->getCreature());
    }
    public function testOnAdultWithNoClouds(): void
    {
        $pdo = new PDOStub();
        $creature = $this->getCreature($pdo);

        $nebulaGlisanto = new NebulaGlisanto(new Standard($pdo, $creature));

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
                'imgurl_m' => 'corge.png',
                'imgurl_f' => 'grault.png',
            ],
        ]);

        $result = $nebulaGlisanto->getLayers();


        $this->assertCount(3, $result);
        $this->assertStringContainsString('public/foo.png', $result[0]);
        $this->assertStringContainsString('public/corge.png', $result[1]);
        $this->assertNull($result[2]);
    }

    public function testOnJuvenileWithClouds(): void
    {
        $pdo = new PDOStub();
        $creature = $this->getCreature($pdo, NebulaGlisanto::CLOUDS_DUSK, GrowthStage::Juvenile);

        $nebulaGlisanto = new NebulaGlisanto(new Standard($pdo, $creature));

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
                'imgurl_m' => 'corge.png',
                'imgurl_f' => 'grault.png',
            ],
        ]);

        $result = $nebulaGlisanto->getLayers();


        $this->assertCount(2, $result);
        $this->assertStringContainsString('public/foo.png', $result[0]);
        $this->assertStringContainsString('public/corge.png', $result[1]);
    }

    #[DataProvider('getClouds')]
    public function testClouds(int $cloudType, ?string $expectedResult): void
    {
        $pdo = new PDOStub();
        $creature = $this->getCreature($pdo, $cloudType);

        $nebulaGlisanto = new NebulaGlisanto(new Standard($pdo, $creature));

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
                'imgurl_m' => 'corge.png',
                'imgurl_f' => 'grault.png',
            ],
        ]);

        $result = $nebulaGlisanto->getLayers();


        $this->assertCount(3, $result);
        $this->assertStringContainsString('public/foo.png', $result[0]);
        $this->assertStringContainsString('public/corge.png', $result[1]);
        if (is_null($expectedResult)) {
            $this->assertNull($result[2]);
        } else {
            $this->assertStringContainsString($expectedResult, $result[2]);
        }
    }

    public static function getClouds(): array
    {
        $clouds = [];
        foreach (NebulaGlisanto::$directory as $cloudType => $cloudPath) {
            $clouds[] = [$cloudType, $cloudPath];
        }
        return $clouds;
    }

    private function getCreature(
        PDOStub     $pdo,
        int         $clouds = 0,
        GrowthStage $growthStage = GrowthStage::Adult
    ): Creature
    {
        $pdo->addData(
            [
                [
                    'speciesId' => Species::NebulaGlisanto->value,
                    'flag' => $clouds,
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
