<?php

namespace App\Service\FileServices;

use App\Service\Filesystem\FilesystemWorker;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\String\Slugger\SluggerInterface;

class FileSaver
{
    private SluggerInterface $slugger;
    private string $uploadsTempDir;
    private FilesystemWorker $filesystemWorker;

    public function __construct(
        SluggerInterface $slugger,
        string $uploadsTempDir,
        FilesystemWorker $filesystemWorker
    )
    {

        $this->slugger = $slugger;
        $this->uploadsTempDir = $uploadsTempDir;
        $this->filesystemWorker = $filesystemWorker;
    }

    public function saveUploadedFileIntoTemp(UploadedFile $uploadedFile): string
    {
        $originalFilename = pathinfo($uploadedFile->getClientOriginalName(), PATHINFO_FILENAME);
        $saveFilename = $this->slugger->slug($originalFilename);

        $filename = sprintf('%s-%s.%s', $saveFilename, uniqid('', false), $uploadedFile->guessExtension());

        $this->filesystemWorker->createFolderIfItNotExist($this->uploadsTempDir);

        try {
            $uploadedFile->move($this->uploadsTempDir, $filename);
        } catch (FileException $e) {
            throw new FileException($e->getMessage());
        }

        return $filename;
    }
}