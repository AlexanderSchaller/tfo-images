<?php
declare(strict_types=1);

namespace app\images\compositions;

use app\core\database\PDO;
use app\game\Creature;
use app\game\Genetics;
use app\images\helpers\GeneticsTransformer;
use AppDir;

class DentegaSalto implements Composition
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
        $path = '/public/img/bases/dentegasalto'
            . '/' . $this->getGrowthStageFolder()
            . '/ears'
            . '/' . $this->getEarGeneFolder()
            . '/' . $this->getBodyGeneNumber() . '.png';

        $layers = $this->composition->getLayers();
        $horns = array_pop($layers);
        return array_merge(
            $layers,
            [
                AppDir::absolute($path),
                $horns,
            ]
        );
    }

    private function getGrowthStageFolder(): string
    {
        return 's' . $this->composition->getCreature()->getGrowthStage()->value;
    }

    private function getEarGeneFolder(): string
    {
        $genetics = new Genetics();
        $earGeneValue = $genetics->getGeneValueByLabel($this->composition->getCreature()->getGenes(), 'Ears');
        return match ($earGeneValue) {
            'AA' => 'lop',
            'Aa' => 'regular',
            'aa' => 'desert',
        };
    }

    private function getBodyGeneNumber(): int
    {
        $genetics = new Genetics();
        $gene = $genetics->getGeneValueByLabel($this->composition->getCreature()->getGenes(), 'Body');
        return GeneticsTransformer::getAsNumber($gene);
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
