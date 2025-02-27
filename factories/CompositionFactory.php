<?php
declare(strict_types=1);

namespace app\images\factories;

use app\core\database\PDO;
use app\game\Creature;
use app\game\creature\GrowthStage;
use app\game\Species;
use app\images\compositions\Capsule;
use app\images\compositions\Composition;
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
use app\images\compositions\Standard;
use app\images\compositions\TransiraAlsalto;
use app\images\compositions\ViraBeko;

class CompositionFactory
{
    public static function get(PDO $database, Creature $creature): Composition
    {
        $standard = new Standard($database, $creature);

        if (array_key_exists($creature->getCode(), NPCCreature::$directory)) {
            return new NPCCreature($standard);
        }

        if ($creature->getGrowthStage() === GrowthStage::Capsule) {
            return new Capsule($standard);
        }

        return match ($creature->getSpecies()) {
            Species::TransiraAlsalto => new TransiraAlsalto($standard),
            Species::KlaraAlsalto => new KlaraAlsalto($standard),
            Species::DentegaSalto => new DentegaSalto($standard),
            Species::LumaMordo => new LumaMordo($standard),
            Species::GlaciaAlsalto => new GlaciaAlsalto($standard),
            Species::MuskaFelo => new MuskaFelo($standard),
            Species::RidaFrakaso => new RidaFrakaso($standard),
            Species::Ranbleko => new Ranbleko($standard),
            Species::EbenaKuranto => new EbenaKuranto($standard),
            Species::NebulaGlisanto => new NebulaGlisanto($standard),
            Species::ViraBeko => new ViraBeko($standard),
            Species::SilentaSpuristo => new SilentaSpuristo($standard),
            Species::EkvinoskaKavigo => new EkvinoskaKavigo($standard),
            default => $standard
        };
    }
}
