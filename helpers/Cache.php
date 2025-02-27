<?php
declare(strict_types=1);

namespace app\images\helpers;

use app\core\database\PDO;
use app\core\Server;
use app\game\Creature;
use app\game\creature\GrowthStage;
use app\images\controllers\ImageController;
use ServerTime;

class Cache
{
    private PDO $database;

    public function __construct(PDO $database)
    {
        $this->database = $database;
    }

    public function getSecondsToCache(Creature $creature): int
    {
        if (
            $creature->getGrowthStage() === GrowthStage::Adult
            || $creature->getCreatureState($this->database)->isStunted()
        ) {
            return ServerTime::SECONDS_IN_A_MONTH;
        }

        $secondsUntilNextGrowthEvent = 0;
        if ($creature->getGrowthStage() === GrowthStage::Capsule) {
            $secondsUntilNextGrowthEvent = $creature->getTimeEmerged() - ServerTime::now();
        } else {
            $secondsUntilNextGrowthEvent = $creature->getTimeMatured() - ServerTime::now();
        }

        if ($secondsUntilNextGrowthEvent <= 1) {
            return 0;
        }

        // Allow a buffer for drift as the next grow time shifts based off of clicks.
        // For new release example creatures (Corteo's, posted on the forum, high traffic),
        // we want to have a 30% window based on how long we have left to grow.
        if (
            $creature->getOwner() === ImageController::CORTEO_USER_ID
            && str_contains(Server::getHttpReferer(), 'forum.finaloutpost.net')
        ) {
            return (int)($secondsUntilNextGrowthEvent * .7);
        }
        // For normal users, a 15% window should suffice.
        return (int)($secondsUntilNextGrowthEvent * .85);
    }
}
