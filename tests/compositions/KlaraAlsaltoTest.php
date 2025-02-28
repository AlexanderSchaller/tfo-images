<?php
declare(strict_types=1);

namespace app\tests\images\compositions;

use app\game\creature\CreatureOrigin;
use app\game\creature\Gender;
use app\game\creature\GrowthStage;
use app\game\creature\Loaded;
use app\game\Gene;
use app\game\Species;
use app\images\compositions\KlaraAlsalto;
use app\images\compositions\Standard;
use tests\stubs\PDO as PDOStub;
use tests\TestCase;

class KlaraAlsaltoTest extends TestCase
{
    public function testAdultComboA(): void
    {
        $pdo = new PDOStub();
        $klaraAlsalto = new KlaraAlsalto(
            new Standard(
                $pdo,
                $this->getCreature(
                    $pdo,
                    'Body:AABBCC,Tails:AA',
                    GrowthStage::Adult
                )
            )
        );

        $pdo->addData([
            ['part' => 'body'],
            ['part' => 'tails'],
        ]);
        $this->addImageRows($pdo, 'Body:AABBCC,Tails:AA', GrowthStage::Adult);

        $result = $klaraAlsalto->getLayers();
  
        $this->assertCount(3, $result);
        $this->assertStringContainsString('/public/img/bases/klaraalsalto/s3/tailm/25/9.png', $result[0]);
    }

    public function testAdultComboB(): void
    {
        $pdo = new PDOStub();
        $klaraAlsalto = new KlaraAlsalto(
            new Standard(
                $pdo,
                $this->getCreature(
                    $pdo,
                    'Body:AaBbCc,Tails:Aa',
                    GrowthStage::Adult
                )
            )
        );

        $pdo->addData([
            ['part' => 'body'],
            ['part' => 'tails'],
        ]);
        $this->addImageRows($pdo, 'Body:AaBbCc,Tails:Aa', GrowthStage::Adult);

        $result = $klaraAlsalto->getLayers();
        $this->assertCount(3, $result);
        $this->assertStringContainsString('/public/img/bases/klaraalsalto/s3/tailm/19/6.png', $result[0]);
    }

    public function testAdultComboC(): void
    {
        $pdo = new PDOStub();
        $klaraAlsalto = new KlaraAlsalto(
            new Standard(
                $pdo,
                $this->getCreature(
                    $pdo,
                    'Body:aabbcc,Tails:aa',
                    GrowthStage::Adult
                )
            )
        );

        $pdo->addData([
            ['part' => 'body'],
            ['part' => 'tails'],
        ]);
        $this->addImageRows($pdo, 'Body:aabbcc,Tails:aa', GrowthStage::Adult);

        $result = $klaraAlsalto->getLayers();
        $this->assertCount(3, $result);
        $this->assertStringContainsString('/public/img/bases/klaraalsalto/s3/tailm/1/3.png', $result[0]);
    }

    public function testJuvenile(): void
    {
        $pdo = new PDOStub();
        $klaraAlsalto = new KlaraAlsalto(
            new Standard(
                $pdo,
                $this->getCreature(
                    $pdo,
                    'Body:AABBCC,Tails:AA',
                    GrowthStage::Juvenile
                )
            )
        );

        $pdo->addData([
            ['part' => 'body'],
            ['part' => 'tails'],
        ]);
        $this->addImageRows($pdo, 'Body:AABBCC,Tails:AA', GrowthStage::Juvenile);

        $result = $klaraAlsalto->getLayers();
        $this->assertCount(2, $result);
    }

    private function addImageRows(PDOStub $pdo, string $genetics, GrowthStage $growthStage): void
    {
        $rows = [];
        foreach (explode(',', $genetics) as $genePair) {
            $gene = new Gene($genePair);
            $rows[] = [
                'part' => strtolower($gene->getLabel()),
                'growth_level' => $growthStage->value,
                'genetic_code' => $genePair,
                'imgurl_m' => 'foo.png',
                'imgurl_f' => 'bar.png',
            ];
        }

        $pdo->addData($rows);
    }

    private function getCreature(
        PDOStub     $pdo,
        string      $genes,
        GrowthStage $growthStage,
    ): Loaded
    {
        $pdo->addData(
            [
                [
                    'speciesId' => Species::KlaraAlsalto->value,
                    'flag' => 0,
                    'name' => '',
                    'growthStageId' => $growthStage->value,
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
                    'genes' => $genes,
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
