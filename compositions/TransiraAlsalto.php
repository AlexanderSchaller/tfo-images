<?php
declare(strict_types=1);

namespace app\images\compositions;

use app\core\database\PDO;
use app\game\Creature;
use app\game\creature\GrowthStage;
use app\game\Genetics;
use AppDir;

class TransiraAlsalto implements Composition
{
    private Composition $composition;
    private array $tailMap = [
        'AABBCC' => [
            'AA' => 'img/bases/alsalto/s3/tails/AA_9_1.png',
            'Aa' => 'img/bases/alsalto/s3/tails/AA_6_1.png',
            'aa' => 'img/bases/alsalto/s3/tails/AA_3_1.png',
        ],
        'AABBCc' => [
            'AA' => 'img/bases/klaraalsalto/s3/tailm/13/9.png',
            'Aa' => 'img/bases/klaraalsalto/s3/tailm/13/6.png',
            'aa' => 'img/bases/klaraalsalto/s3/tailm/13/3.png',
        ],
        'AABBcc' => [
            'AA' => 'img/bases/klaraalsalto/s3/tailm/24/9.png',
            'Aa' => 'img/bases/klaraalsalto/s3/tailm/24/6.png',
            'aa' => 'img/bases/klaraalsalto/s3/tailm/24/3.png',
        ],
        'AABbCC' => [
            'AA' => 'img/bases/alsalto/s3/tails/Aa_9.png',
            'Aa' => 'img/bases/alsalto/s3/tails/Aa_6.png',
            'aa' => 'img/bases/alsalto/s3/tails/Aa_3.png',
        ],
        'AABbCc' => [
            'AA' => 'img/bases/klaraalsalto/s3/tailm/25/9.png',
            'Aa' => 'img/bases/klaraalsalto/s3/tailm/25/6.png',
            'aa' => 'img/bases/klaraalsalto/s3/tailm/25/3.png',
        ],
        'AABbcc' => [
            'AA' => 'img/bases/klaraalsalto/s3/tailm/11/9.png',
            'Aa' => 'img/bases/klaraalsalto/s3/tailm/11/6.png',
            'aa' => 'img/bases/klaraalsalto/s3/tailm/11/3.png',
        ],
        'AAbbCC' => [
            'AA' => 'img/bases/klaraalsalto/s3/tailm/5/9.png',
            'Aa' => 'img/bases/klaraalsalto/s3/tailm/5/6.png',
            'aa' => 'img/bases/klaraalsalto/s3/tailm/5/3.png',
        ],
        'AAbbCc' => [
            'AA' => 'img/bases/klaraalsalto/s3/tailm/7/9.png',
            'Aa' => 'img/bases/klaraalsalto/s3/tailm/7/6.png',
            'aa' => 'img/bases/klaraalsalto/s3/tailm/7/3.png',
        ],
        'AAbbcc' => [
            'AA' => 'img/bases/klaraalsalto/s3/tailm/10/9.png',
            'Aa' => 'img/bases/klaraalsalto/s3/tailm/10/6.png',
            'aa' => 'img/bases/klaraalsalto/s3/tailm/10/3.png',
        ],
        'AaBBCC' => [
            'AA' => 'img/bases/klaraalsalto/s3/tailm/4/9.png',
            'Aa' => 'img/bases/klaraalsalto/s3/tailm/4/6.png',
            'aa' => 'img/bases/klaraalsalto/s3/tailm/4/3.png',
        ],
        'AaBBCc' => [
            'AA' => 'img/bases/klaraalsalto/s3/tailm/12/9.png',
            'Aa' => 'img/bases/klaraalsalto/s3/tailm/12/6.png',
            'aa' => 'img/bases/klaraalsalto/s3/tailm/12/3.png',
        ],
        'AaBBcc' => [
            'AA' => 'img/bases/klaraalsalto/s3/tailm/3/9.png',
            'Aa' => 'img/bases/klaraalsalto/s3/tailm/3/6.png',
            'aa' => 'img/bases/klaraalsalto/s3/tailm/3/3.png',
        ],
        'AaBbCC' => [
            'AA' => 'img/bases/klaraalsalto/s3/tailm/19/9.png',
            'Aa' => 'img/bases/klaraalsalto/s3/tailm/19/6.png',
            'aa' => 'img/bases/klaraalsalto/s3/tailm/19/3.png',
        ],
        'AaBbCc' => [
            'AA' => 'img/bases/klaraalsalto/s3/tailm/19/9.png',
            'Aa' => 'img/bases/klaraalsalto/s3/tailm/19/6.png',
            'aa' => 'img/bases/klaraalsalto/s3/tailm/19/3.png',
        ],
        'AaBbcc' => [
            'AA' => 'img/bases/klaraalsalto/s3/tailm/27/9.png',
            'Aa' => 'img/bases/klaraalsalto/s3/tailm/27/6.png',
            'aa' => 'img/bases/klaraalsalto/s3/tailm/27/3.png',
        ],
        'AabbCC' => [
            'AA' => 'img/bases/klaraalsalto/s3/tailm/17/9.png',
            'Aa' => 'img/bases/klaraalsalto/s3/tailm/17/6.png',
            'aa' => 'img/bases/klaraalsalto/s3/tailm/17/3.png',
        ],
        'AabbCc' => [
            'AA' => 'img/bases/klaraalsalto/s3/tailm/16/9.png',
            'Aa' => 'img/bases/klaraalsalto/s3/tailm/16/6.png',
            'aa' => 'img/bases/klaraalsalto/s3/tailm/16/3.png',
        ],
        'Aabbcc' => [
            'AA' => 'img/bases/klaraalsalto/s3/tailm/2/9.png',
            'Aa' => 'img/bases/klaraalsalto/s3/tailm/2/6.png',
            'aa' => 'img/bases/klaraalsalto/s3/tailm/2/3.png',
        ],
        'aaBBCC' => [
            'AA' => 'img/bases/klaraalsalto/s3/tailm/8/9.png',
            'Aa' => 'img/bases/klaraalsalto/s3/tailm/8/6.png',
            'aa' => 'img/bases/klaraalsalto/s3/tailm/8/3.png',
        ],
        'aaBBCc' => [
            'AA' => 'img/bases/klaraalsalto/s3/tailm/18/9.png',
            'Aa' => 'img/bases/klaraalsalto/s3/tailm/18/6.png',
            'aa' => 'img/bases/klaraalsalto/s3/tailm/18/3.png',
        ],
        'aaBBcc' => [
            'AA' => 'img/bases/klaraalsalto/s3/tailm/6/9.png',
            'Aa' => 'img/bases/klaraalsalto/s3/tailm/6/6.png',
            'aa' => 'img/bases/klaraalsalto/s3/tailm/6/3.png',
        ],
        'aaBbCC' => [
            'AA' => 'img/bases/klaraalsalto/s3/tailm/19/9.png',
            'Aa' => 'img/bases/klaraalsalto/s3/tailm/19/6.png',
            'aa' => 'img/bases/klaraalsalto/s3/tailm/19/3.png',
        ],
        'aaBbCc' => [
            'AA' => 'img/bases/klaraalsalto/s3/tailm/26/9.png',
            'Aa' => 'img/bases/klaraalsalto/s3/tailm/26/6.png',
            'aa' => 'img/bases/klaraalsalto/s3/tailm/26/3.png',
        ],
        'aaBbcc' => [
            'AA' => 'img/bases/klaraalsalto/s3/tailm/9/9.png',
            'Aa' => 'img/bases/klaraalsalto/s3/tailm/9/6.png',
            'aa' => 'img/bases/klaraalsalto/s3/tailm/9/3.png',
        ],
        'aabbCC' => [
            'AA' => 'img/bases/klaraalsalto/s3/tailm/14/9.png',
            'Aa' => 'img/bases/klaraalsalto/s3/tailm/14/6.png',
            'aa' => 'img/bases/klaraalsalto/s3/tailm/14/3.png',
        ],
        'aabbCc' => [
            'AA' => 'img/bases/klaraalsalto/s3/tailm/15/9.png',
            'Aa' => 'img/bases/klaraalsalto/s3/tailm/15/6.png',
            'aa' => 'img/bases/klaraalsalto/s3/tailm/15/3.png',
        ],
        'aabbcc' => [
            'AA' => 'img/bases/klaraalsalto/s3/tailm/1/9.png',
            'Aa' => 'img/bases/klaraalsalto/s3/tailm/1/6.png',
            'aa' => 'img/bases/klaraalsalto/s3/tailm/1/3.png',
        ],
    ];

    public function __construct(Composition $composition)
    {
        $this->composition = $composition;
    }

    public function getLayers(): array
    {
        $layers = $this->composition->getLayers();
        if ($this->composition->getCreature()->getGrowthStage() !== GrowthStage::Adult) {
            return $layers;
        }


        $genetics = new Genetics();
        $bodyGene = $genetics->getGeneValueByLabel($this->composition->getCreature()->getGenes(), 'Body');
        $tailsGene = $genetics->getGeneValueByLabel($this->composition->getCreature()->getGenes(), 'Tails');
        $path = '/public/' . $this->tailMap[$bodyGene][$tailsGene];

        $fireLayer = array_pop($layers);
        $iceLayer = array_pop($layers);
        return array_merge(
            $layers,
            [
                AppDir::absolute($path),
                $fireLayer,
                $iceLayer,
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
