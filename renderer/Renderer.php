<?php
declare(strict_types=1);

namespace app\images\renderer;

use claviska\SimpleImage;

interface Renderer
{
    public function render(SimpleImage $image): void;
}
