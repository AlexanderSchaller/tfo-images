<?php
declare(strict_types=1);

namespace app\images\helpers;

class GeneticsTransformer
{
    public static function getAsNumber(string $geneValue): int
    {
        $genes = str_split($geneValue, 2);

        $result = 0;
        foreach ($genes as $gene) {
            // 'AABBCC' = 1 + 0 + 0 = 1
            // ...
            // 'AaBBCc' = 4 + 0 + 9 = 13
            // 'AaBbCc' = 4 + 1 + 9 = 14
            // 'AabbCc' = 4 + 2 + 9 = 15
            // 'aaBBCc' = 7 + 0 + 9 = 16
            // ...
            // 'aabbcc' = 7 + 2 + 18 = 27
            $result += match ($gene) {
                'CC' => 0,
                'Cc' => 9,
                'cc' => 18,
                'BB' => 0,
                'Bb' => 1,
                'bb' => 2,
                'AA' => 1,
                'Aa' => 4,
                'aa' => 7,
            };
        }
        return $result;
    }
}
