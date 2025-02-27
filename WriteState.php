<?php
declare(strict_types=1);

namespace app\images;

use app\core\database\PDO;
use app\game\Creature;
use app\game\database\Query;

class WriteState
{
    private PDO $database;
    private Creature $creature;

    public function __construct(PDO $database, Creature $creature)
    {
        $this->database = $database;
        $this->creature = $creature;
    }

    public function recordWrite(): void
    {
        Query::assertBuildAndExecute(
            'UPDATE
                cats_owned_cats
            SET
                mature_file_write = 1
            WHERE
                code = :code',
            $this->database,
            [
                'code' => $this->creature->getCode(),
            ]
        );
    }
}
