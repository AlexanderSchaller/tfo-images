<?php
declare(strict_types=1);

namespace app\images\compositions;

use app\core\database\PDO;
use app\game\Creature;

interface Composition
{
    /**
     * @return string[]|null[]
     */
    public function getLayers(): array;

    public function getCreature(): Creature;

    public function getDatabase(): PDO;
}
