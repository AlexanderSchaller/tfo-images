<?php
declare(strict_types=1);

namespace app\images\compositions;

use app\core\database\PDO;
use app\game\Creature;
use app\game\creature\flags\Carrot;
use app\game\creature\GrowthStage;
use AppDir;

class EkvinoskaKavigo implements Composition
{
    private Composition $composition;

    public function __construct(Composition $composition)
    {
        $this->composition = $composition;
    }

    public function getLayers(): array
    {
        if (
            $this->composition->getCreature()->getFlag() !== Carrot::VISIBLE
            || $this->composition->getCreature()->getGrowthStage() !== GrowthStage::Adult
        ) {
            return $this->composition->getLayers();
        }

        return array_merge(
            $this->composition->getLayers(),
            [
                AppDir::absolute('/public/img/bases/ekvinoskakavigo/carrot.png'),
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
