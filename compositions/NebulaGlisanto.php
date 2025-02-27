<?php
declare(strict_types=1);

namespace app\images\compositions;

use app\core\database\PDO;
use app\game\Creature;
use app\game\creature\GrowthStage;
use AppDir;

class NebulaGlisanto implements Composition
{
    public const NO_CLOUDS = 0;
    public const CLOUDS_DAY = 1;
    public const CLOUDS_DUSK = 2;
    public const CLOUDS_NIGHT = 3;
    public const CLOUDS_STORM = 4;

    private Composition $composition;
    public static array $directory = [
        self::CLOUDS_DAY => '/public/img/bases/nebulaglisanto/s3/clouds/day.png',
        self::CLOUDS_DUSK => '/public/img/bases/nebulaglisanto/s3/clouds/dusk.png',
        self::CLOUDS_NIGHT => '/public/img/bases/nebulaglisanto/s3/clouds/night.png',
        self::CLOUDS_STORM => '/public/img/bases/nebulaglisanto/s3/clouds/storm.png',
        self::NO_CLOUDS => null,
    ];

    public function __construct(Composition $composition)
    {
        $this->composition = $composition;
    }

    public function getLayers(): array
    {
        if ($this->composition->getCreature()->getGrowthStage() !== GrowthStage::Adult) {
            return $this->composition->getLayers();
        }

        $cloud = self::$directory[$this->composition->getCreature()->getFlag()];
        if (!is_null($cloud)) {
            $cloud = AppDir::absolute($cloud);
        }

        return array_merge(
            $this->composition->getLayers(),
            [
                $cloud,
            ]
        );
    }

    public function getCreature(): Creature
    {
        return $this->composition->getCreature();
    }

    public function getDatabase(): PDO
    {
        return $this->composition->getDatabase();
    }
}
