<?php
declare(strict_types=1);

namespace app\images\compositions;

use app\core\database\PDO;
use app\game\Creature;
use app\game\NotImplementedException;
use AppDir;

class MissingImage implements Composition
{
    public function getLayers(): array
    {
        return [
            AppDir::absolute('public/img/ui/missingImg.png'),
        ];
    }

    public function getCreature(): Creature
    {
        throw new NotImplementedException('Intentionally not implemented');
    }

    public function getDatabase(): PDO
    {
        throw new NotImplementedException('Intentionally not implemented');
    }
}
