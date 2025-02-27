<?php
declare(strict_types=1);

namespace app\images\compositions;

use app\core\database\PDO;
use app\game\Creature;
use app\game\creature\GrowthStage;
use app\game\Genetics;
use AppDir;

class RidaFrakaso implements Composition
{
    public const LIGHT_ANTLER = 'AABB';
    public const DARK_ANTLER = 'aabb';
    public const POSITION_FAR = 'far';
    public const POSITION_NEAR = 'near';

    private Composition $composition;

    public static array $directory = [
        self::LIGHT_ANTLER => [
            self::POSITION_NEAR => '/public/img/bases/ridafrakaso/s3/antlers/1-n.png',
            self::POSITION_FAR => '/public/img/bases/ridafrakaso/s3/antlers/1-f.png',
        ],
        self::DARK_ANTLER => [
            self::POSITION_NEAR => '/public/img/bases/ridafrakaso/s3/antlers/9-n.png',
            self::POSITION_FAR => '/public/img/bases/ridafrakaso/s3/antlers/9-f.png',
        ],
    ];

    public function __construct(Composition $composition)
    {
        $this->composition = $composition;
    }

    /**
     * @inheritDoc
     */
    public function getLayers(): array
    {
        if ($this->composition->getCreature()->getGrowthStage() !== GrowthStage::Adult) {
            return $this->composition->getLayers();
        }

        $genetics = new Genetics();
        $gene = $genetics->getGeneValueByLabel($this->composition->getCreature()->getGenes(), 'Antlers');

        return array_merge(
            [
                $this->getAntler($gene, self::POSITION_FAR),
            ],
            $this->composition->getLayers(),
            [
                $this->getAntler($gene, self::POSITION_NEAR),
            ]
        );
    }

    private function getAntler(string $geneValue, string $position): ?string
    {
        if (!array_key_exists($geneValue, self::$directory)) {
            return null;
        }

        return AppDir::absolute(self::$directory[$geneValue][$position]);
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
