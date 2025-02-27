<?php
declare(strict_types=1);

namespace app\images\compositions;

use app\core\database\PDO;
use app\game\Creature;
use AppDir;

class NPCCreature implements Composition
{
    public const ROY_SHADE_NORA = 'j2WUZ';
    public const CORAL_M_MUI_SUNDA_HUNDO = 'hDXjj';
    public const SOZA_STARRS_JACKSON = 'HNDAj';
    public const IRIS_EVADA_LIMAKA_CEVALOS = 'PWs2f';
    public const ROSA_DHEY_NEBULA_GLISANTO = '68VTa';
    public const AGNES_SCHANI_GLUBLEKO = '95hlK';

    public static array $directory = [
        self::ROY_SHADE_NORA => 'public/img/custom/nora.png',
        self::CORAL_M_MUI_SUNDA_HUNDO => 'public/img/custom/buppy.png',
        self::SOZA_STARRS_JACKSON => 'public/img/custom/HNDAj.png',
        self::IRIS_EVADA_LIMAKA_CEVALOS => 'public/img/custom/PWs2f.png',
        self::ROSA_DHEY_NEBULA_GLISANTO => 'public/img/custom/asmaan.png',
        self::AGNES_SCHANI_GLUBLEKO => 'public/img/custom/95hlK.png',
    ];

    private Composition $composition;

    public function __construct(Composition $composition)
    {
        $this->composition = $composition;
    }

    public function getLayers(): array
    {
        return [
            AppDir::absolute(self::$directory[$this->composition->getCreature()->getCode()]),
        ];
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
