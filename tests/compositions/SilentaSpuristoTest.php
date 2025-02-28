<?php
declare(strict_types=1);

namespace app\tests\images\compositions;

use app\game\creature\CreatureOrigin;
use app\game\creature\Gender;
use app\game\creature\GrowthStage;
use app\game\creature\Loaded;
use app\game\Species;
use app\images\compositions\SilentaSpuristo;
use app\images\compositions\Standard;
use tests\stubs\PDO as PDOStub;
use tests\TestCase;

class SilentaSpuristoTest extends TestCase
{
    public function testAdultThirdCoat(): void
    {
        $pdo = new PDOStub();
        $glaciaAlsalto = new SilentaSpuristo(
            new Standard(
                $pdo,
                $this->getCreature(
                    $pdo,
                    'AABBCC',
                    'AA'
                )
            )
        );

        $pdo->addData([
            ['part' => 'body'],
            ['part' => 'tails'],
        ]);
        $pdo->addData([
            [
                'part' => 'body',
                'growth_level' => GrowthStage::Adult->value,
                'genetic_code' => 'Body:AABBCC',
                'imgurl_m' => 'foo.png',
                'imgurl_f' => 'bar.png',
            ],
            [
                'part' => 'tails',
                'growth_level' => GrowthStage::Adult->value,
                'genetic_code' => 'Tails:AA',
                'imgurl_m' => 'corge.png',
                'imgurl_f' => 'grault.png',
            ],
        ]);
        $result = $glaciaAlsalto->getLayers();
        $this->assertCount(3, $result);
        $this->assertStringContainsString('/public/img/bases/silentaspuristo/s3/tails/1/1.png', $result[2]);
    }

    public function testAdultDifferentCoat(): void
    {
        $pdo = new PDOStub();
        $glaciaAlsalto = new SilentaSpuristo(
            new Standard(
                $pdo,
                $this->getCreature(
                    $pdo,
                    'AaBBCc',
                    'AA'
                )
            )
        );

        $pdo->addData([
            ['part' => 'body'],
            ['part' => 'tails'],
        ]);
        $pdo->addData([
            [
                'part' => 'body',
                'growth_level' => GrowthStage::Adult->value,
                'genetic_code' => 'Body:AaBBCc',
                'imgurl_m' => 'foo.png',
                'imgurl_f' => 'bar.png',
            ],
            [
                'part' => 'tails',
                'growth_level' => GrowthStage::Adult->value,
                'genetic_code' => 'Tails:AA',
                'imgurl_m' => 'corge.png',
                'imgurl_f' => 'grault.png',
            ],
        ]);
        $result = $glaciaAlsalto->getLayers();
        $this->assertCount(3, $result);
        $this->assertStringContainsString('/public/img/bases/silentaspuristo/s3/tails/13/1.png', $result[2]);
    }

    public function testAdult(): void
    {
        $pdo = new PDOStub();
        $glaciaAlsalto = new SilentaSpuristo(
            new Standard(
                $pdo,
                $this->getCreature(
                    $pdo,
                    'AAbbCC',
                    'AA'
                )
            )
        );

        $pdo->addData([
            ['part' => 'body'],
            ['part' => 'tails'],
        ]);
        $pdo->addData([
            [
                'part' => 'body',
                'growth_level' => GrowthStage::Adult->value,
                'genetic_code' => 'Body:AAbbCC',
                'imgurl_m' => 'foo.png',
                'imgurl_f' => 'bar.png',
            ],
            [
                'part' => 'tails',
                'growth_level' => GrowthStage::Adult->value,
                'genetic_code' => 'Tails:AA',
                'imgurl_m' => 'corge.png',
                'imgurl_f' => 'grault.png',
            ],
        ]);
        $result = $glaciaAlsalto->getLayers();
        $this->assertCount(3, $result);
        $this->assertStringContainsString('/public/img/bases/silentaspuristo/s3/tails/3/1.png', $result[2]);
    }

    private function getCreature(PDOStub $pdo, string $bodyGene, string $tailsGene): Loaded
    {
        $pdo->addData(
            [
                [
                    'speciesId' => Species::GlaciaAlsalto->value,
                    'flag' => 0,
                    'name' => '',
                    'growthStageId' => GrowthStage::Adult->value,
                    'userId' => 1,
                    'previousOwnerId' => 1,
                    'originalOwnerId' => 1,
                    'obtainedTime' => 2,
                    'emergeTime' => 3,
                    'matureTime' => 4,
                    'mother' => null,
                    'father' => null,
                    'appearance' => 5,
                    'happiness' => 6,
                    'hardiness' => 7,
                    'views' => 8,
                    'gender' => Gender::Male->value,
                    'genes' => 'Body:' . $bodyGene . ',Tails:' . $tailsGene,
                    'tab' => 9,
                    'gottenFrom' => CreatureOrigin::Cupboard->value,
                ],
            ]
        );

        return new Loaded(
            'abcde',
            $pdo
        );
    }
}
