<?php
declare(strict_types=1);

namespace app\tests\images\helpers;

use app\game\creature\GrowthStage;
use app\game\creature\state\CreatureState;
use app\images\helpers\Cache;
use ServerTime;
use tests\stubs\PDO as PDOStub;
use tests\TestCase;

class CacheTest extends TestCase
{
    public function testGetSecondsToCacheForAdult(): void
    {
        $pdo = new PDOStub();
        $cache = new Cache($pdo);
        $result = $cache->getSecondsToCache($this->getTestCreature(growthStage: GrowthStage::Adult));
        $this->assertSame(
            ServerTime::SECONDS_IN_A_MONTH,
            $result
        );
    }

    public function testGetSecondsToCacheForStuntedCreature(): void
    {
        $pdo = new PDOStub();
        $pdo->addData(
            [
                [
                    'frozen' => 1,
                    'inTrade' => 0,
                    'inBreeding' => 0,
                    'inGifting' => 0,
                    'influenced' => 0,
                    'incubated' => 0,
                    'sequenced' => 0,
                    'clickMultiplier' => 0,
                    'lastBred' => 0,
                    'abandonedHash' => null,
                    'predictedGenetics' => null,
                    'genesRerolled' => false,
                    'whenJuvenileGrowthSequenced' => CreatureState::INACTIVE_FLAG,
                    'predictedEmergeTime' => 0,
                ],
            ]
        );

        $cache = new Cache($pdo);
        $creature = $this->getTestCreature(growthStage: GrowthStage::Juvenile);

        $result = $cache->getSecondsToCache($creature);
        $this->assertSame(
            ServerTime::SECONDS_IN_A_MONTH,
            $result
        );
    }

    public function testGetSecondsToCacheForCapsule(): void
    {
        $pdo = new PDOStub();
        $pdo->addData(
            [
                [
                    'frozen' => 0,
                    'inTrade' => 0,
                    'inBreeding' => 0,
                    'inGifting' => 0,
                    'influenced' => 0,
                    'incubated' => 0,
                    'sequenced' => 0,
                    'clickMultiplier' => 0,
                    'lastBred' => 0,
                    'abandonedHash' => null,
                    'predictedGenetics' => null,
                    'genesRerolled' => false,
                    'whenJuvenileGrowthSequenced' => CreatureState::INACTIVE_FLAG,
                    'predictedEmergeTime' => 0,
                ],
            ]
        );

        $cache = new Cache($pdo);
        $creature = $this->getTestCreature(
            timeEmergedDelta: ServerTime::SECONDS_IN_A_DAY * 2
        );

        $result = $cache->getSecondsToCache($creature);
        $this->assertSame(
            146881,
            $result
        );
    }

    public function testGetSecondsToCacheForJuvenile(): void
    {
        $pdo = new PDOStub();
        $pdo->addData(
            [
                [
                    'frozen' => 0,
                    'inTrade' => 0,
                    'inBreeding' => 0,
                    'inGifting' => 0,
                    'influenced' => 0,
                    'incubated' => 0,
                    'sequenced' => 0,
                    'clickMultiplier' => 0,
                    'lastBred' => 0,
                    'abandonedHash' => null,
                    'predictedGenetics' => null,
                    'genesRerolled' => false,
                    'whenJuvenileGrowthSequenced' => CreatureState::INACTIVE_FLAG,
                    'predictedEmergeTime' => 0,
                ],
            ]
        );

        $cache = new Cache($pdo);
        $creature = $this->getTestCreature(
            growthStage: GrowthStage::Juvenile,
            timeMaturedDelta: ServerTime::SECONDS_IN_A_DAY * 2
        );

        $result = $cache->getSecondsToCache($creature);
        $this->assertSame(
            146881,
            $result
        );
    }

    public function testGetSecondsToCacheForCapsuleEmergingSoon(): void
    {
        $pdo = new PDOStub();
        $pdo->addData(
            [
                [
                    'frozen' => 0,
                    'inTrade' => 0,
                    'inBreeding' => 0,
                    'inGifting' => 0,
                    'influenced' => 0,
                    'incubated' => 0,
                    'sequenced' => 0,
                    'clickMultiplier' => 0,
                    'lastBred' => 0,
                    'abandonedHash' => null,
                    'predictedGenetics' => null,
                    'genesRerolled' => false,
                    'whenJuvenileGrowthSequenced' => CreatureState::INACTIVE_FLAG,
                    'predictedEmergeTime' => 0,
                ],
            ]
        );

        $cache = new Cache($pdo);
        $creature = $this->getTestCreature();

        $result = $cache->getSecondsToCache($creature);
        $this->assertSame(
            0,
            $result
        );
    }

    public function testGetSecondsToCacheForJuvenileMaturingSoon(): void
    {
        $pdo = new PDOStub();
        $pdo->addData(
            [
                [
                    'frozen' => 0,
                    'inTrade' => 0,
                    'inBreeding' => 0,
                    'inGifting' => 0,
                    'influenced' => 0,
                    'incubated' => 0,
                    'sequenced' => 0,
                    'clickMultiplier' => 0,
                    'lastBred' => 0,
                    'abandonedHash' => null,
                    'predictedGenetics' => null,
                    'genesRerolled' => false,
                    'whenJuvenileGrowthSequenced' => CreatureState::INACTIVE_FLAG,
                    'predictedEmergeTime' => 0,
                ],
            ]
        );

        $cache = new Cache($pdo);
        $creature = $this->getTestCreature(
            growthStage: GrowthStage::Juvenile
        );

        $result = $cache->getSecondsToCache($creature);
        $this->assertSame(
            1,
            $result
        );
    }
}
