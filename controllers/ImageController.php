<?php
declare(strict_types=1);

namespace app\images\controllers;

use app\classes\EndExecution;
use app\core\Controller;
use app\core\Folder;
use app\game\creature\Assembled;
use app\game\creature\CreatureOrigin;
use app\game\creature\GrowthStage;
use app\game\creature\Loaded;
use app\game\factories\GenderFactory;
use app\game\factories\SpeciesFactory;
use app\game\factories\SpeciesNotesAdditionalTogglesFactory;
use app\game\Genetics as GeneticsEngine;
use app\game\GeneticSequence;
use app\game\NotImplementedException;
use app\images\compositions\Composition;
use app\images\compositions\MissingImage;
use app\images\factories\CompositionFactory;
use app\images\helpers\Cache;
use app\images\Image;
use app\images\renderer\ForTwitter;
use app\images\renderer\Renderer;
use app\images\renderer\ToFile;
use app\images\renderer\ToScreen;
use app\images\WriteState;
use AppDir;
use Exception;
use ServerTime;

class ImageController extends Controller
{
    public const int ADMIN_USER_ID = 1;

    public function renderLabNotesCreature(): void
    {
        try {
            $species = SpeciesFactory::getFromHash(
                $this->database,
                $this->request->getQueryParam('s', '')
            );
        } catch (Exception) {
            $species = null;
        }

        $gender = GenderFactory::makeFromString($this->request->getQueryParam('g', ''));
        if (is_null($species) || !$gender->isValid()) {
            $this->renderMissingImage(new ToScreen());
            EndExecution::endExecution();
        }

        $geneticEngine = new GeneticsEngine();
        $genesFromQuery = $this->request->getQueryParam('c', '');
        $additionalImages = SpeciesNotesAdditionalTogglesFactory::get($species);
        $imageAdditionName = SpeciesNotesAdditionalTogglesFactory::getImageAdditionName($additionalImages);
        $flagValue = 0;
        $genes = (new GeneticSequence($genesFromQuery))->toString();

        if (!$geneticEngine->areValid($genes, $species, $this->database)) {
            $this->renderMissingImage(new ToScreen());
            EndExecution::endExecution();
        }

        if (!is_null($imageAdditionName)) {
            $genes = $geneticEngine->removeGene(
                new GeneticSequence($genesFromQuery),
                ucfirst($imageAdditionName)
            );

            $flagValue = $additionalImages::getFlagValue(
                $geneticEngine->getGeneValueByLabel(
                    new GeneticSequence($genesFromQuery),
                    ucfirst($imageAdditionName)
                )
            );
        }
        $assembledCreature = new Assembled(
            '',
            $species,
            $flagValue,
            GrowthStage::Adult,
            0,
            0,
            0,
            0,
            0,
            0,
            null,
            null,
            0,
            0,
            0,
            0,
            $gender,
            $genes,
            [],
            '',
            0,
            CreatureOrigin::Cupboard,
        );

        $composition = new MissingImage();
        if ($assembledCreature->getSpeciesState($this->database)->isUnlocked()) {
            $composition = CompositionFactory::get($this->database, $assembledCreature);
        }

        $this->setHeaders(ServerTime::SECONDS_IN_A_MONTH);
        $this->renderImage($composition, new ToScreen());
    }

    public function renderForTwitter(string $code): void
    {
        $this->renderImage($this->getComposition($code), new ForTwitter());
    }

    public function renderCachedCreature(string $code): void
    {
        // a fallback for when a static cache image hasn't been generated yet (i.e capsules and unstunted juveniles)
        $this->renderCreature($code);
    }

    public function renderCreature(string $code): void
    {
        $code = substr($code, 0, 5);

        $composition = $this->getComposition($code);
        if (
            !($composition instanceof MissingImage)
            && (
                $composition->getCreature()->getGrowthStage() === GrowthStage::Adult
                || $composition->getCreature()->getCreatureState($this->database)->isStunted()
            )
        ) {

            // The static cache is located in /public/s.  Public facing images are presented with
            // https://finaloutpost/s/code.png.  Due to the way that the .htaccess file is set up in the public folder
            // (web root), it will first try to locate the static file there.  If it does not exist, it will be
            // redirected to this method via routes.php.  The image will be build and stored in /public/s/ for sequential
            // viewing and displayed to the user for current view.

            $image = new Image($composition);
            $image->render(
                new ToFile(
                    new Folder(AppDir::absolute('/public/s')),
                    $composition->getCreature()->getCode()
                )
            );

            $writeState = new WriteState($this->database, $composition->getCreature());
            $writeState->recordWrite();
        }

        try {
            $cache = new Cache($this->database);
            $secondsToCache = $cache->getSecondsToCache($composition->getCreature());
        } catch (NotImplementedException) {
            $secondsToCache = 3600;
        }
        $this->setHeaders($secondsToCache);
        $this->renderImage($composition, new ToScreen());
    }

    private function getComposition(string $code): Composition
    {
        try {
            $creature = new Loaded($code, $this->database);
            return CompositionFactory::get($this->database, $creature);
        } catch (Exception) {
            // this exception was thrown because the creature does not exist in the database.
            return new MissingImage();
        }
    }

    private function renderImage(Composition $composition, Renderer $renderer): void
    {
        $imageBuilder = new Image($composition);
        try {
            $imageBuilder->render($renderer);
        } catch (Exception) {
            // this exception was thrown as a file path or image data was invalid.
            $this->renderMissingImage($renderer);
        }
        EndExecution::endExecution();
    }

    private function renderMissingImage(Renderer $renderer): void
    {
        (new Image(new MissingImage()))->render($renderer);
    }

    /**
     * @param int $secondsToCache
     * @return void
     */
    public function setHeaders(int $secondsToCache): void
    {
        $this->response->setHeader(
            'Expires',
            ServerTime::date(
                'D, d M Y H:i:s \G\M\T',
                (ServerTime::now() + $secondsToCache)
            )
        );
        $this->response->setHeader(
            'Cache-Control',
            'max-age=' . $secondsToCache
        );
        $this->response->setHeader(
            'Pragma',
            'cache'
        );
    }
}
