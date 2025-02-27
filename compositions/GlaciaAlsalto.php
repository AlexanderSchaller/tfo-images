<?php
declare(strict_types=1);

namespace app\images\compositions;

use app\core\database\PDO;
use app\game\Creature;
use app\game\Genetics;
use AppDir;

class GlaciaAlsalto implements Composition
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
        $tailPath = '/public/img/bases/alsalto/'
            . $this->getGrowthStageFolder()
            . '/tails/'
            . $this->getFileName()
            . '.png';

        $layers = $this->composition->getLayers();
        $fireLayer = array_pop($layers);
        $iceLayer = array_pop($layers);
        return array_merge(
            $layers,
            [
                AppDir::absolute($tailPath),
                $fireLayer,
                $iceLayer,
            ]
        );
    }

    private function getFileName(): string
    {
        $genetics = new Genetics();
        $bodyGene = $genetics->getGeneValueByLabel($this->composition->getCreature()->getGenes(), 'Body');
        $tailsGene = $genetics->getGeneValueByLabel($this->composition->getCreature()->getGenes(), 'Tails');
        return $this->getTailPrefix($tailsGene, $bodyGene) . $this->getTailSuffix($bodyGene);
    }

    private function getTailSuffix(string $gene): string
    {
        $chunk = str_split($gene, 2);
        return match (reset($chunk)) {
            'AA' => '_1',
            'aa' => '_2',
            default => ''
        };
    }

    private function getTailPrefix(string $tailsGene, string $bodyGene): string
    {
        return match ($tailsGene) {
            'AA' => $this->getCoatBasedPrefix($bodyGene) . '_9',
            'Aa' => $this->getCoatBasedPrefix($bodyGene) . '_6',
            'aa' => $this->getCoatBasedPrefix($bodyGene) . '_3'
        };
    }

    private function getCoatBasedPrefix(string $gene): string
    {
        $chunk = str_split($gene, 2);
        return reset($chunk);
    }

    private function getGrowthStageFolder(): string
    {
        return 's' . $this->composition->getCreature()->getGrowthStage()->value;
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
