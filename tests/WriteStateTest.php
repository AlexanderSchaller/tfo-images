<?php
declare(strict_types=1);

namespace app\tests\images;

use app\game\creature\CreatureOrigin;
use app\game\creature\Gender;
use app\game\creature\GrowthStage;
use app\game\creature\Loaded;
use app\game\Species;
use app\images\WriteState;
use tests\stubs\PDO as PDOStub;
use tests\TestCase;

class WriteStateTest extends TestCase
{
    public function testRecordWrite(): void
    {
        $pdo = new PDOStub();
        $writeState = new WriteState($pdo, $this->getCreature($pdo));
        $writeState->recordWrite();
        $this->assertNthQueryConforms(
            $pdo,
            2,
            [
                'UPDATE',
                'cats_owned_cats',
                'SET',
                'mature_file_write = 1',
                'WHERE',
                'code = :code',
            ],
            [
                'code' => 'abcde',
            ]
        );
    }

    private function getCreature(PDOStub $pdo): Loaded
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
                    'genes' => 'Body:AA,Head:AABBCC',
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
