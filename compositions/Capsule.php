<?php
declare(strict_types=1);

namespace app\images\compositions;

use app\core\database\PDO;
use app\game\Creature;
use AppDir;

class Capsule implements Composition
{
    private Composition $composition;

    public function __construct(Composition $composition)
    {
        $this->composition = $composition;
    }

    public function getLayers(): array
    {
        return [
            AppDir::absolute(
                '/public/' . $this->composition->getCreature()
                    ->getSpeciesData($this->composition->getDatabase())
                    ->getCapsuleImage()
            ),
        ];
    }

    public function getDatabase(): PDO
    {
        return $this->composition->getDatabase();
    }

    public function getCreature(): Creature
    {
        return $this->composition->getCreature();
    }
}
