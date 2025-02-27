<?php
declare(strict_types=1);

namespace app\images\renderer;

use claviska\SimpleImage;

class ToScreen implements Renderer
{
    public function render(SimpleImage $image): void
    {
        $image->toScreen();
    }
}
