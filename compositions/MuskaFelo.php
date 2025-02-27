<?php
declare(strict_types=1);

namespace app\images\compositions;

use app\core\database\PDO;
use app\game\Creature;
use app\game\creature\GrowthStage;
use app\game\Genetics;
use AppDir;

class MuskaFelo implements Composition
{
    private Composition $composition;

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

        $path = '/public/img/bases/muskafelo/s3/' . $this->getEarsFolder() . '/' . $this->getFileNumber() . '.png';
        return array_merge(
            $this->composition->getLayers(),
            [
                AppDir::absolute($path),
            ]
        );
    }

    private function getFileNumber(): int
    {
        $genetics = new Genetics();
        $gene = $genetics->getGeneValueByLabel($this->composition->getCreature()->getGenes(), 'Body');
        return match ($gene) {
            'AABB' => 1,
            'AABb' => 6,
            'AAbb' => 8,
            'AaBB' => 3,
            'AaBb' => 9,
            'Aabb' => 7,
            'aaBB' => 5,
            'aaBb' => 4,
            'aabb' => 2,
        };
    }

    private function getEarsFolder(): string
    {
        $genetics = new Genetics();
        $gene = $genetics->getGeneValueByLabel($this->composition->getCreature()->getGenes(), 'Length');
        return match ($gene) {
            'AA' => 'earslg',
            'Aa' => 'earsmd',
            'aa' => 'earssm',
        };
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
