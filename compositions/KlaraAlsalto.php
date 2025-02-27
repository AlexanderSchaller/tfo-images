<?php
declare(strict_types=1);

namespace app\images\compositions;

use app\core\database\PDO;
use app\game\Creature;
use app\game\creature\GrowthStage;
use app\game\Genetics;
use AppDir;

class KlaraAlsalto implements Composition
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
        $layers = $this->composition->getLayers();
        if ($this->composition->getCreature()->getGrowthStage() !== GrowthStage::Adult) {
            return $layers;
        }

        $path = '/public/img/bases/klaraalsalto/s3/tailm/'
            . $this->getBodyFolder()
            . '/'
            . $this->getFileNumber()
            . '.png';

        $fireLayer = array_pop($layers);
        $iceLayer = array_pop($layers);
        return array_merge(
            $layers,
            [
                AppDir::absolute($path),
                $fireLayer,
                $iceLayer,
            ]
        );
    }

    private function getBodyFolder(): int
    {
        $genetics = new Genetics();
        $gene = $genetics->getGeneValueByLabel($this->composition->getCreature()->getGenes(), 'Body');
        return match ($gene) {
            'AABBCC' => 25,
            'AABBCc' => 13,
            'AABBcc' => 24,
            'AABbCC' => 19,
            'AABbCc' => 25,
            'AABbcc' => 11,
            'AAbbCC' => 5,
            'AAbbCc' => 7,
            'AAbbcc' => 10,
            'AaBBCC' => 4,
            'AaBBCc' => 12,
            'AaBBcc' => 3,
            'AaBbCC' => 19,
            'AaBbCc' => 19,
            'AaBbcc' => 27,
            'AabbCC' => 17,
            'AabbCc' => 16,
            'Aabbcc' => 2,
            'aaBBCC' => 8,
            'aaBBCc' => 18,
            'aaBBcc' => 6,
            'aaBbCC' => 19,
            'aaBbCc' => 26,
            'aaBbcc' => 9,
            'aabbCC' => 14,
            'aabbCc' => 15,
            'aabbcc' => 1
        };
    }

    private function getFileNumber(): int
    {
        $genetics = new Genetics();
        $gene = $genetics->getGeneValueByLabel($this->composition->getCreature()->getGenes(), 'Tails');
        return match ($gene) {
            'AA' => 9,
            'Aa' => 6,
            'aa' => 3,
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
