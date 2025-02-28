<?php
declare(strict_types=1);

namespace app\tests\images\compositions;

use app\game\Creature;
use app\game\creature\Gender;
use app\game\creature\GrowthStage;
use app\game\Gene;
use app\game\Species;
use app\images\compositions\LumaMordo;
use app\images\compositions\Standard;
use tests\stubs\PDO as PDOStub;
use tests\TestCase;

class LumaMordoTest extends TestCase
{
    public function testMaleAdultComboA(): void
    {
        $pdo = new PDOStub();
        $lumaMordo = new LumaMordo(
            new Standard(
                $pdo,
                $this->getTestCreature(
                    gender: Gender::Male,
                    genes: 'Body:AAbb,Head:AaBb,Variation:AABB',
                    growthStage: GrowthStage::Adult,
                    species: Species::LumaMordo,
                )
            )
        );
        $pdo->addData([
            ['part' => 'body'],
            ['part' => 'head'],
            ['part' => 'variation'],
        ]);
        $this->addImageRows($pdo, 'Body:AAbb,Head:AaBb,Variation:AABB', GrowthStage::Adult);
        $result = $lumaMordo->getLayers();

        $this->assertCount(4, $result);
        $this->assertStringContainsString('/public/img/bases/lumamordo/s3/head/3_RRgg/1.png', $result[3]);
    }

    public function testMaleAdultComboB(): void
    {
        $pdo = new PDOStub();
        $lumaMordo = new LumaMordo(
            new Standard(
                $pdo,
                $this->getTestCreature(
                    gender: Gender::Male,
                    genes: 'Body:Aabb,Head:aabb,Variation:Aabb',
                    growthStage: GrowthStage::Adult,
                    species: Species::LumaMordo,
                )
            )
        );
        $pdo->addData([
            ['part' => 'body'],
            ['part' => 'head'],
            ['part' => 'variation'],
        ]);
        $this->addImageRows($pdo, 'Body:Aabb,Head:aabb,Variation:Aabb', GrowthStage::Adult);
        $result = $lumaMordo->getLayers();

        $this->assertCount(4, $result);
        $this->assertStringContainsString('/public/img/bases/lumamordo/s3/head/6_Rrgg/8.png', $result[3]);
    }

    public function testFemaleAdultComboA(): void
    {
        $pdo = new PDOStub();
        $lumaMordo = new LumaMordo(
            new Standard(
                $pdo,
                $this->getTestCreature(
                    gender: Gender::Female,
                    genes: 'Body:AaBb,Head:AAbb,Variation:aabb',
                    growthStage: GrowthStage::Adult,
                    species: Species::LumaMordo,
                )
            )
        );
        $pdo->addData([
            ['part' => 'body'],
            ['part' => 'head'],
            ['part' => 'variation'],
        ]);
        $this->addImageRows($pdo, 'Body:AaBb,Head:AAbb,Variation:aabb', GrowthStage::Adult);
        $result = $lumaMordo->getLayers();

        $this->assertCount(4, $result);
        $this->assertStringContainsString('/public/img/bases/lumamordo/s3/lures/3_RRgg/9.png', $result[3]);
    }

    public function testFemaleAdultComboB(): void
    {
        $pdo = new PDOStub();
        $lumaMordo = new LumaMordo(
            new Standard(
                $pdo,
                $this->getTestCreature(
                    gender: Gender::Female,
                    genes: 'Body:AABB,Head:AABB,Variation:AABB',
                    growthStage: GrowthStage::Adult,
                    species: Species::LumaMordo,
                )
            )
        );
        $pdo->addData([
            ['part' => 'body'],
            ['part' => 'head'],
            ['part' => 'variation'],
        ]);
        $this->addImageRows($pdo, 'Body:AABB,Head:AABB,Variation:AABB', GrowthStage::Adult);
        $result = $lumaMordo->getLayers();

        $this->assertCount(4, $result);
        $this->assertStringContainsString('/public/img/bases/lumamordo/s3/lures/1_RRGG/1.png', $result[3]);
    }

    public function testGetJuvenile(): void
    {
        $pdo = new PDOStub();
        $lumaMordo = new LumaMordo(
            new Standard(
                $pdo,
                $this->getTestCreature(
                    genes: 'Body:AABB,Head:AABB,Variation:AABB',
                    growthStage: GrowthStage::Juvenile,
                    species: Species::LumaMordo,
                )
            )
        );
        $pdo->addData([
            ['part' => 'body'],
            ['part' => 'head'],
            ['part' => 'variation'],
        ]);
        $this->addImageRows($pdo, 'Body:AABB,Head:AABB,Variation:AABB', GrowthStage::Juvenile);

        $result = $lumaMordo->getLayers();
        $this->assertCount(3, $result);
    }

    public function testGetDatabase(): void
    {
        $pdo = new PDOStub();
        $lumaMordo = new LumaMordo(
            new Standard(
                $pdo,
                $this->getTestCreature(
                    species: Species::LumaMordo,
                )
            )
        );
        $this->assertInstanceOf(PDOStub::class, $lumaMordo->getDatabase());
    }

    public function testGetCreature(): void
    {
        $pdo = new PDOStub();
        $lumaMordo = new LumaMordo(
            new Standard(
                $pdo,
                $this->getTestCreature(
                    species: Species::LumaMordo,
                )
            )
        );
        $this->assertInstanceOf(Creature::class, $lumaMordo->getCreature());
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
}
