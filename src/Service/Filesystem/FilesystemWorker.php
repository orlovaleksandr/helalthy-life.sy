<?php

namespace App\Service\Filesystem;

use Symfony\Component\Filesystem\Filesystem;

class FilesystemWorker
{
    private Filesystem $filesystem;

    public function __construct(Filesystem $filesystem)
    {
        $this->filesystem = $filesystem;
    }

    public function createFolderIfItNotExist(string $folder): void
    {
        if (!$this->filesystem->exists($folder)) {
            $this->filesystem->mkdir($folder);
        }
    }
}