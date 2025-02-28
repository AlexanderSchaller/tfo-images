<?php
declare(strict_types=1);

namespace app\tests\images\compositions;

use app\game\Creature;
use app\game\creature\CreatureOrigin;
use app\game\creature\Gender;
use app\game\creature\GrowthStage;
use app\game\creature\Loaded;
use app\game\Species;
use app\images\compositions\NPCCreature;
use app\images\compositions\Standard;
use PHPUnit\Framework\Attributes\DataProvider;
use tests\stubs\PDO as PDOStub;
use tests\TestCase;

class NPCCreatureTest extends TestCase
{
    public function testGetDatabase(): void
    {
        $pdo = new PDOStub();
        $standard = new Standard($pdo, $this->getCreature($pdo, 'abcde'));

        $capsule = new NPCCreature($standard);
        $this->assertInstanceOf(PDOStub::class, $capsule->getDatabase());
    }

    public function testGetCreature(): void
    {
        $pdo = new PDOStub();
        $standard = new Standard($pdo, $this->getCreature($pdo, 'abcde'));

        $capsule = new NPCCreature($standard);
        $this->assertInstanceOf(Creature::class, $capsule->getCreature());
    }

    #[DataProvider('getCreatures')]
    public function testGetLayers(string $creatureCode, string $expectedUrl): void
    {
        $pdo = new PDOStub();
        $NPCCreature = new NPCCreature(
            new Standard(
                $pdo,
                $this->getCreature(
                    $pdo,
                    $creatureCode
                )
            )
        );

        $result = $NPCCreature->getLayers();
        $this->assertStringContainsString($expectedUrl, reset($result));
    }

    public static function getCreatures(): array
    {
        $dataset = [];
        foreach (NPCCreature::$directory as $code => $url) {
            $dataset[] = [$code, $url];
        }
        return $dataset;
    }

    private function getCreature(
        PDOStub $pdo,
        string  $code,
    ): Creature
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
                    'genes' => 'Body:AA,Tail:aa',
                    'tab' => 9,
                    'gottenFrom' => CreatureOrigin::Cupboard->value,
                ],
            ]
        );

        return new Loaded(
            $code,
            $pdo
        );
    }
}
