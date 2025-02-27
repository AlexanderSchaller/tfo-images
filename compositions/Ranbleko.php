<?php
declare(strict_types=1);

namespace app\images\compositions;

use app\core\database\PDO;
use app\game\Creature;
use app\game\creature\GrowthStage;
use AppDir;

class Ranbleko implements Composition
{
    public const NO_TONGUE = 0;
    public const TONGUE_UP = 1;
    public const TONGUE_DOWN = 2;
    private Composition $composition;
    public static array $directory = [
        self::TONGUE_UP => '/public/img/bases/ranbleko/s3/blep/1.png',
        self::TONGUE_DOWN => '/public/img/bases/ranbleko/s3/blep/2.png',
        self::NO_TONGUE => null,
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

        $tongue = self::$directory[$this->composition->getCreature()->getFlag()];
        if (!is_null($tongue)) {
            $tongue = AppDir::absolute($tongue);
        }

        return array_merge(
            $this->composition->getLayers(),
            [
                $tongue,
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
