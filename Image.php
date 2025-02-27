<?php
declare(strict_types=1);

namespace app\images;

use app\images\compositions\Composition;
use app\images\renderer\Renderer;
use claviska\SimpleImage;
use Exception;

class Image
{
    private SimpleImage $engine;
    private Composition $composition;

    public function __construct(Composition $composition, SimpleImage $engine = new Engine())
    {
        $this->composition = $composition;
        $this->engine = $engine;
    }

    /**
     * @throws Exception
     */
    public function render(Renderer $renderer): void
    {
        $layers = array_filter($this->composition->getLayers());
        $image = $this->engine->fromFile(array_shift($layers));

        foreach ($layers as $layer) {
            $this->engine->overlay($layer);
        }

        $renderer->render($image);
    }
}
