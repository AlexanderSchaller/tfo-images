<?php
declare(strict_types=1);

namespace app\images\compositions;

use app\core\database\PDO;
use app\game\Creature;
use app\game\creature\Gender;
use app\game\creature\GrowthStage;
use app\game\Genetics;
use AppDir;

class LumaMordo implements Composition
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

        $gender = $this->composition->getCreature()->getGender();
        $path = '/public/img/bases/lumamordo/s3/'
            . $this->getGenderBasedFolder($gender)
            . '/'
            . $this->getGeneBasedFolder($gender)
            . '/'
            . $this->getFileNumber()
            . '.png';

        return array_merge(
            $this->composition->getLayers(),
            [
                AppDir::absolute($path),
            ]
        );
    }

    private function getGeneBasedFolder(Gender $gender): string
    {
        $genetics = new Genetics();
        if ($gender === Gender::Male) {
            $gene = $genetics->getGeneValueByLabel($this->composition->getCreature()->getGenes(), 'Body');
        } else {
            $gene = $genetics->getGeneValueByLabel($this->composition->getCreature()->getGenes(), 'Head');
        }

        return match ($gene) {
            'AABB' => '1_RRGG',
            'AABb' => '2_RRGg',
            'AAbb' => '3_RRgg',
            'AaBB' => '4_RrGG',
            'AaBb' => '5_RrGg',
            'Aabb' => '6_Rrgg',
            'aaBB' => '7_rrGG',
            'aaBb' => '8_rrGg',
            'aabb' => '9_rrgg',
        };
    }

    private function getFileNumber(): int
    {
        $genetics = new Genetics();
        $gene = $genetics->getGeneValueByLabel($this->composition->getCreature()->getGenes(), 'Variation');
        return match ($gene) {
            'AABB' => 1,
            'AABb' => 4,
            'AAbb' => 7,
            'AaBB' => 2,
            'AaBb' => 5,
            'Aabb' => 8,
            'aaBB' => 3,
            'aaBb' => 6,
            'aabb' => 9
        };
    }

    private function getGenderBasedFolder(Gender $gender): string
    {
        if ($gender === Gender::Male) {
            return 'head';
        }

        return 'lures';
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
