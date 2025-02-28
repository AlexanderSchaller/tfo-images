<?php
declare(strict_types=1);

namespace app\tests\images\compositions;

use app\game\Creature;
use app\game\creature\CreatureOrigin;
use app\game\creature\Gender;
use app\game\creature\GrowthStage;
use app\game\creature\Loaded;
use app\game\Species;
use app\images\compositions\Capsule;
use app\images\compositions\Standard;
use tests\stubs\PDO as PDOStub;
use tests\TestCase;

class CapsuleTest extends TestCase
{
    public function testGetDatabase(): void
    {
        $pdo = new PDOStub();
        $standard = new Standard($pdo, $this->getCreature($pdo));

        $capsule = new Capsule($standard);
        $this->assertInstanceOf(PDOStub::class, $capsule->getDatabase());
    }

    public function testGetCreature(): void
    {
        $pdo = new PDOStub();
        $standard = new Standard($pdo, $this->getCreature($pdo));

        $capsule = new Capsule($standard);
        $this->assertInstanceOf(Creature::class, $capsule->getCreature());
    }

    public function testGetLayers(): void
    {
        $pdo = new PDOStub();
        $standard = new Standard($pdo, $this->getCreature($pdo));

        $capsule = new Capsule($standard);
        $pdo->addData([['orderedGeneParts' => '']]);
        $result = $capsule->getLayers();

        $this->assertStringContainsString('public/img/path/baq.png', $result[0]);
    }

    private function getCreature($pdo): Creature
    {
        $pdo->addData(
            [
                [
                    'speciesId' => Species::ViraBeko->value,
                    'flag' => 0,
                    'name' => '',
                    'growthStageId' => GrowthStage::Capsule->value,
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
                    'genes' => 'Body:AA',
                    'tab' => 9,
                    'gottenFrom' => CreatureOrigin::Cupboard->value,
                ],
            ]
        );

        $pdo->addData(
            [
                [
                    'public_code' => 'fghij',
                    'breed_name' => 'Vira Beko',
                    'description' => 'Foo',
                    'description_s2' => 'Bar',
                    'description_s3' => 'Baz',
                    'capsule_img' => 'img/path/baq.png',
                    'orderedGeneParts' => 'body',
                    'length' => '1m',
                    'weight' => '2kg',
                    'height' => '3cm',
                    'released_time' => 10,
                ],
            ]
        );

        return new Loaded(
            'abcde',
            $pdo
        );
    }
}
