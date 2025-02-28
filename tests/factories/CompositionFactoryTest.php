<?php
declare(strict_types=1);

namespace app\tests\images\factories;

use app\game\Creature;
use app\game\creature\CreatureOrigin;
use app\game\creature\Gender;
use app\game\creature\GrowthStage;
use app\game\creature\Loaded;
use app\game\Species;
use app\images\compositions\Capsule;
use app\images\compositions\DentegaSalto;
use app\images\compositions\EbenaKuranto;
use app\images\compositions\EkvinoskaKavigo;
use app\images\compositions\GlaciaAlsalto;
use app\images\compositions\KlaraAlsalto;
use app\images\compositions\LumaMordo;
use app\images\compositions\MuskaFelo;
use app\images\compositions\NebulaGlisanto;
use app\images\compositions\NPCCreature;
use app\images\compositions\Ranbleko;
use app\images\compositions\RidaFrakaso;
use app\images\compositions\SilentaSpuristo;
use app\images\compositions\TransiraAlsalto;
use app\images\compositions\ViraBeko;
use app\images\factories\CompositionFactory;
use tests\stubs\PDO as PDOStub;
use tests\TestCase;

class CompositionFactoryTest extends TestCase
{
    public function testEkvinoskaKavigoDecorator(): void
    {
        $pdo = new PDOStub();
        $result = CompositionFactory::get(
            $pdo,
            $this->getCreature(
                $pdo,
                Species::EkvinoskaKavigo,
                GrowthStage::Adult,
            )
        );

        $this->assertInstanceOf(EkvinoskaKavigo::class, $result);
    }

    public function testSilentaSpuristoDecorator(): void
    {
        $pdo = new PDOStub();
        $result = CompositionFactory::get(
            $pdo,
            $this->getCreature(
                $pdo,
                Species::SilentaSpuristo,
                GrowthStage::Adult,
            )
        );

        $this->assertInstanceOf(SilentaSpuristo::class, $result);
    }

    public function testTransiraAlsaltoDecorator(): void
    {
        $pdo = new PDOStub();
        $result = CompositionFactory::get(
            $pdo,
            $this->getCreature(
                $pdo,
                Species::TransiraAlsalto,
                GrowthStage::Adult,
            )
        );

        $this->assertInstanceOf(TransiraAlsalto::class, $result);
    }

    public function testLumaMordoDecorator(): void
    {
        $pdo = new PDOStub();
        $result = CompositionFactory::get(
            $pdo,
            $this->getCreature(
                $pdo,
                Species::LumaMordo,
                GrowthStage::Adult,
            )
        );

        $this->assertInstanceOf(LumaMordo::class, $result);
    }

    public function testGetKlaraAlsaltoDecorator(): void
    {
        $pdo = new PDOStub();
        $result = CompositionFactory::get(
            $pdo,
            $this->getCreature(
                $pdo,
                Species::KlaraAlsalto,
                GrowthStage::Adult,
            )
        );

        $this->assertInstanceOf(KlaraAlsalto::class, $result);
    }

    public function testMuskaFeloDecorator(): void
    {
        $pdo = new PDOStub();
        $result = CompositionFactory::get(
            $pdo,
            $this->getCreature(
                $pdo,
                Species::MuskaFelo,
                GrowthStage::Adult,
            )
        );

        $this->assertInstanceOf(MuskaFelo::class, $result);
    }

    public function testGlaciaAlsaltoDecorator(): void
    {
        $pdo = new PDOStub();
        $result = CompositionFactory::get(
            $pdo,
            $this->getCreature(
                $pdo,
                Species::GlaciaAlsalto,
                GrowthStage::Adult,
            )
        );

        $this->assertInstanceOf(GlaciaAlsalto::class, $result);
    }

    public function testDentegaSaltoReturnsDentegaSaltoDecorator(): void
    {
        $pdo = new PDOStub();
        $result = CompositionFactory::get(
            $pdo,
            $this->getCreature(
                $pdo,
                Species::DentegaSalto,
                GrowthStage::Adult
            )
        );

        $this->assertInstanceOf(DentegaSalto::class, $result);
    }

    public function testNebulaGlisantoDecorator(): void
    {
        $pdo = new PDOStub();
        $result = CompositionFactory::get(
            $pdo,
            $this->getCreature(
                $pdo,
                Species::NebulaGlisanto,
                GrowthStage::Adult,
            )
        );

        $this->assertInstanceOf(NebulaGlisanto::class, $result);
    }

    public function testRidaFrakasoDecorator(): void
    {
        $pdo = new PDOStub();
        $result = CompositionFactory::get(
            $pdo,
            $this->getCreature(
                $pdo,
                Species::RidaFrakaso,
                GrowthStage::Adult,
            )
        );

        $this->assertInstanceOf(RidaFrakaso::class, $result);
    }

    public function testEbenaKurantoDecorator(): void
    {
        $pdo = new PDOStub();
        $result = CompositionFactory::get(
            $pdo,
            $this->getCreature(
                $pdo,
                Species::EbenaKuranto,
                GrowthStage::Adult,
            )
        );

        $this->assertInstanceOf(EbenaKuranto::class, $result);
    }

    public function testViraBekoReturnsViraBekoDecorator(): void
    {
        $pdo = new PDOStub();
        $result = CompositionFactory::get(
            $pdo,
            $this->getCreature(
                $pdo,
                Species::ViraBeko,
                GrowthStage::Adult,
            )
        );

        $this->assertInstanceOf(ViraBeko::class, $result);
    }

    public function testRanblekoReturnsRanblekoDecorator(): void
    {
        $pdo = new PDOStub();
        $result = CompositionFactory::get(
            $pdo,
            $this->getCreature(
                $pdo,
                Species::Ranbleko,
                GrowthStage::Adult,
            )
        );

        $this->assertInstanceOf(Ranbleko::class, $result);
    }

    public function testNPCCreatureReturnsNPCCreatureDecorator(): void
    {
        $pdo = new PDOStub();
        $result = CompositionFactory::get(
            $pdo,
            $this->getCreature(
                $pdo,
                Species::MontaSelo,
                GrowthStage::Adult,
                NPCCreature::ROY_SHADE_NORA
            )
        );

        $this->assertInstanceOf(NPCCreature::class, $result);
    }

    public function testCapsuleReturnsCapsuleDecorator(): void
    {
        $pdo = new PDOStub();
        $result = CompositionFactory::get(
            $pdo,
            $this->getCreature(
                $pdo,
                Species::ViraBeko,
                GrowthStage::Capsule
            )
        );

        $this->assertInstanceOf(Capsule::class, $result);
    }

    private function getCreature(PDOStub $pdo, Species $species, GrowthStage $growthStage, string $code = 'abcde'): Creature
    {
        $pdo->addData(
            [
                [
                    'speciesId' => $species->value,
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
                    'genes' => 'Body:AA,Tail:aa',
                    'tab' => 9,
                    'gottenFrom' => CreatureOrigin::Cupboard->value,
                ],
            ]
        );

        return new Loaded(
            $code,
            $pdo
        );
    }
}
