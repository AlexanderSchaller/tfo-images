<?php
declare(strict_types=1);

namespace app\images\compositions;

use app\core\database\PDO;
use app\game\Creature;
use app\game\creature\GrowthStage;
use app\game\Genetics;
use app\images\helpers\GeneticsTransformer;
use AppDir;

class SilentaSpuristo implements Composition
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

        $path = '/public/img/bases/silentaspuristo/s3/tails/'
            . $this->getBodyFolder()
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

    public function getCreature(): Creature
    {
        return $this->composition->getCreature();
    }

    public function getDatabase(): PDO
    {
        return $this->composition->getDatabase();
    }

    private function getBodyFolder(): int
    {
        $genetics = new Genetics();
        $gene = $genetics->getGeneValueByLabel($this->composition->getCreature()->getGenes(), 'Body');
        return GeneticsTransformer::getAsNumber($gene);
    }

    private function getFileNumber(): int
    {
        $genetics = new Genetics();
        $gene = $genetics->getGeneValueByLabel($this->composition->getCreature()->getGenes(), 'Tails');
        return match ($gene) {
            'AA' => 1,
            'Aa' => 2,
            'aa' => 3,
        };
    }
}
