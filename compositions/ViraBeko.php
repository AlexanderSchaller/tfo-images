<?php
declare(strict_types=1);

namespace app\images\compositions;

use app\core\database\PDO;
use app\game\Creature;
use app\game\creature\GrowthStage;
use app\game\Genetics;
use AppDir;

class ViraBeko implements Composition
{
    public const HAS_CIMO = 1;

    private array $leftFacingHeadGenes = [
        'AABbCC',
        'AAbbCC',
        'AaBbCC',
        'AabbCC',
        'AABbCc',
        'AabbCc',
        'aaBbCc',
        'aabbCc',
        'AAbbcc',
        'Aabbcc',
        'aaBbcc',
        'aabbcc',
    ];
    private Composition $composition;

    public function __construct(Composition $composition)
    {
        $this->composition = $composition;
    }

    public function getLayers(): array
    {
        if (
            $this->composition->getCreature()->getFlag() !== self::HAS_CIMO
            || $this->composition->getCreature()->getGrowthStage() !== GrowthStage::Adult
        ) {
            return $this->composition->getLayers();
        }

        $genetics = new Genetics();
        $headImagePath = '/public/img/bases/turkey/s3/cimo/2.png';
        if (in_array(
            $genetics->getGeneValueByLabel($this->composition->getCreature()->getGenes(), 'Head'),
            $this->leftFacingHeadGenes
        )) {
            $headImagePath = '/public/img/bases/turkey/s3/cimo/1.png';
        }

        return array_merge(
            $this->composition->getLayers(),
            [
                AppDir::absolute($headImagePath),
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
