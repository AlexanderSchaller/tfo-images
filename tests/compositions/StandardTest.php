<?php
declare(strict_types=1);

namespace app\tests\images\compositions;

use app\game\creature\Gender;
use app\game\creature\GrowthStage;
use app\images\compositions\Standard;
use tests\stubs\PDO as PDOStub;
use tests\TestCase;

class StandardTest extends TestCase
{
    public function testGetLayersWithFemale(): void
    {
        $pdo = new PDOStub();

        $creature = $this->getTestCreature(
            gender: Gender::Female,
            genes: 'Body:AA,Tail:aa',
            growthStage: GrowthStage::Adult,
        );

        $standard = new Standard($pdo, $creature);

        $pdo->addData([
            ['part' => 'body'],
            ['part' => 'tail'],
        ]);
        $pdo->addData([
            [
                'part' => 'body',
                'growth_level' => GrowthStage::Adult->value,
                'genetic_code' => 'Body:AA',
                'imgurl_m' => 'foo.png',
                'imgurl_f' => 'bar.png',
            ],
            [
                'part' => 'body',
                'growth_level' => GrowthStage::Adult->value,
                'genetic_code' => 'Body:aa',
                'imgurl_m' => 'baz.png',
                'imgurl_f' => 'baq.png',
            ],
            [
                'part' => 'tail',
                'growth_level' => GrowthStage::Adult->value,
                'genetic_code' => 'Tail:AA',
                'imgurl_m' => 'qux.png',
                'imgurl_f' => 'quux.png',
            ],
            [
                'part' => 'tail',
                'growth_level' => GrowthStage::Adult->value,
                'genetic_code' => 'Tail:aa',
                'imgurl_m' => 'corge.png',
                'imgurl_f' => 'grault.png',
            ],
        ]);

        $result = $standard->getLayers();

        $this->assertCount(2, $result);
        $this->assertStringContainsString('public/bar.png', $result[0]);
        $this->assertStringContainsString('public/grault.png', $result[1]);
    }

    public function testGetLayers(): void
    {
        $pdo = new PDOStub();
        $creature = $this->getTestCreature(
            gender: Gender::Male,
            genes: 'Body:AA,Tail:aa',
            growthStage: GrowthStage::Adult,
        );

        $standard = new Standard($pdo, $creature);

        $pdo->addData([
            ['part' => 'body'],
            ['part' => 'tail'],
        ]);
        $pdo->addData([
            [
                'part' => 'body',
                'growth_level' => GrowthStage::Adult->value,
                'genetic_code' => 'Body:AA',
                'imgurl_m' => 'foo.png',
                'imgurl_f' => 'bar.png',
            ],
            [
                'part' => 'body',
                'growth_level' => GrowthStage::Adult->value,
                'genetic_code' => 'Body:aa',
                'imgurl_m' => 'baz.png',
                'imgurl_f' => 'baq.png',
            ],
            [
                'part' => 'tail',
                'growth_level' => GrowthStage::Adult->value,
                'genetic_code' => 'Tail:AA',
                'imgurl_m' => 'qux.png',
                'imgurl_f' => 'quux.png',
            ],
            [
                'part' => 'tail',
                'growth_level' => GrowthStage::Adult->value,
                'genetic_code' => 'Tail:aa',
                'imgurl_m' => 'corge.png',
                'imgurl_f' => 'grault.png',
            ],
        ]);

        $result = $standard->getLayers();

        $this->assertCount(2, $result);
        $this->assertStringContainsString('public/foo.png', $result[0]);
        $this->assertStringContainsString('public/corge.png', $result[1]);
    }
}
