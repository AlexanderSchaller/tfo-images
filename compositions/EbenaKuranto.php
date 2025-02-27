<?php
declare(strict_types=1);

namespace app\images\compositions;

use app\core\database\PDO;
use app\game\Creature;
use app\game\creature\GrowthStage;
use app\game\Genetics;
use AppDir;

class EbenaKuranto implements Composition
{
    public const WING_PEGASUS = 'AABBCc';
    public const WING_JERSEY_DEVIL = 'aaBBCc';

    private Composition $composition;
    public static array $wings = [
        self::WING_PEGASUS => '/public/img/bases/ebenakuranto/s3/wings/pegasus-farwing.png',
        self::WING_JERSEY_DEVIL => '/public/img/bases/ebenakuranto/s3/wings/jerseydevil-farwing.png',
    ];

    public function __construct(Composition $composition)
    {
        $this->composition = $composition;
    }

    public function getLayers(): array
    {
        $farWing = $this->getFarWing($this->composition->getCreature()->getGrowthStage());
        if (is_null($farWing)) {
            return $this->composition->getLayers();
        }

        return array_merge(
            [
                AppDir::absolute($farWing),
            ],
            $this->composition->getLayers()
        );
    }

    private function getFarWing(GrowthStage $growthStage): ?string
    {
        if ($growthStage !== GrowthStage::Adult) {
            return null;
        }

        $genetics = new Genetics();
        $gene = $genetics->getGeneValueByLabel($this->composition->getCreature()->getGenes(), 'Wings');
        if (!array_key_exists($gene, self::$wings)) {
            return null;
        }
        return self::$wings[$gene];
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
