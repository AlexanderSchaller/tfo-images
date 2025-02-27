<?php
declare(strict_types=1);

namespace app\images\renderer;

use app\core\Folder;
use claviska\SimpleImage;
use Exception;

class ToFile implements Renderer
{
    private Folder $folder;
    private string $filename;

    public function __construct(Folder $folder, string $filename)
    {
        $this->folder = $folder;
        $this->filename = $filename;
    }

    /**
     * @param SimpleImage $image
     * @return void
     * @throws Exception If fails to write to file
     */
    public function render(SimpleImage $image): void
    {
        $image->toFile(
            $this->folder->getPath() . '/' . $this->filename . '.png',
            'image/png'
        );
    }
}
