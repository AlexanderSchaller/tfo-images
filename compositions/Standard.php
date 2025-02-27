<?php
declare(strict_types=1);

namespace app\images\compositions;

use app\core\database\PDO;
use app\game\Creature;
use app\game\creature\Gender;
use app\game\database\Query;
use AppDir;

class Standard implements Composition
{
    private static array $layers = [];
    private static array $layerOrder = [];
    private PDO $database;
    private Creature $creature;

    public function __construct(PDO $database, Creature $creature)
    {
        $this->database = $database;
        $this->creature = $creature;
    }

    public function getCreature(): Creature
    {
        return $this->creature;
    }

    public function getDatabase(): PDO
    {
        return $this->database;
    }

    public function getLayers(): array
    {
        $layerOrder = $this->fetchLayerOrder();
        $layers = $this->fetchLayers();

        $genderKey = 'imgurl_m';
        if ($this->creature->getGender() == Gender::Female) {
            $genderKey = 'imgurl_f';
        }

        $rawGenes = $this->creature->getGenes()->get();

        $sortedGenes = [];
        foreach ($layerOrder as $part) {
            $sortedGenes[$part] = $rawGenes[ucfirst($part)];
        }

        $out = [];
        foreach ($sortedGenes as $gene) {
            $layer = $layers[strtolower($gene->getLabel())][$gene->getFull()][$genderKey];
            if (is_null($layer)) {
                $out[] = null;
            } else {
                $out[] = AppDir::absolute('/public/' . $layer);
            }
        }

        return $out;
    }

    private function fetchLayerOrder(): array
    {
        if (!array_key_exists($this->creature->getSpecies()->value, self::$layerOrder)) {
            $layerOrder = Query::fetchColumn(
                'part',
                'SELECT
                    part
                FROM
                    speciesGeneOrder
                WHERE
                    speciesId = :speciesId',
                $this->database,
                [
                    'speciesId' => $this->creature->getSpecies(),
                ]
            );
            self::$layerOrder[$this->creature->getSpecies()->value] = $layerOrder;
        }
        return self::$layerOrder[$this->creature->getSpecies()->value];
    }

    private function fetchLayers(): array
    {
        if (!array_key_exists($this->creature->getSpecies()->value, self::$layers)) {
            self::$layers[$this->creature->getSpecies()->value] = $this->formatMap(
                Query::fetchAll(
                    'SELECT
                        g.part,
                        g.growth_level,
                        g.genetic_code,
                        g.imgurl_m,
                        g.imgurl_f
                    FROM
                        cats_map_bases AS g
                        INNER JOIN speciesGeneOrder AS o ON (
                            o.speciesId = g.cid
                            AND o.part = g.part
                        )
                    WHERE
                        cid = :speciesId
                    ORDER BY
                        o.position',
                    $this->database,
                    [
                        'speciesId' => $this->creature->getSpecies(),
                    ],
                )
            );
        }

        return self::$layers[$this->creature->getSpecies()->value][$this->getCreature()->getGrowthStage()->value];
    }

    /**
     * @param array{
     *     part: string,
     *     growth_level: int,
     *     genetic_code: string,
     *     imgurl_m: ?string,
     *     imgurl_f: ?string,
     * } $map
     * @return array{
     *     growth_level:array{
     *          part: array{
     *              genetic_code: array{
     *                  imgurl_m: ?string,
     *                  imgurl_f: ?string
     *             }
     *         }
     *     }
     * }
     */
    private function formatMap(array $map): array
    {
        $out = [];
        foreach ($map as $row) {
            if (!array_key_exists($row['growth_level'], $out)) {
                $out[$row['growth_level']] = [];
            }

            if (!array_key_exists($row['part'], $out[$row['growth_level']])) {
                $out[$row['growth_level']][$row['part']] = [];
            }

            if (!array_key_exists($row['genetic_code'], $out[$row['growth_level']][$row['part']])) {
                $out[$row['growth_level']][$row['part']][$row['genetic_code']] = [];
            }
            $out[$row['growth_level']][$row['part']][$row['genetic_code']]['imgurl_m'] = $row['imgurl_m'];
            $out[$row['growth_level']][$row['part']][$row['genetic_code']]['imgurl_f'] = $row['imgurl_f'];
        }
        return $out;
    }

    public static function clearLayersCache(): void
    {
        self::$layers = [];
        self::$layerOrder = [];
    }
}
