<?php

namespace App\Listener;

use App\Entity\CadrageFile;
use App\Entity\DeployementFile;
use App\Helper\FileDirectory;
use App\Service\Uploader;
use Doctrine\ORM\Mapping as ORM;

class CadrageFileUploadListener
{
    /**
     * @var Uploader
     */
    private $uploader;

    /**
     * @var string
     */
    private $directory;

    public function __construct(Uploader $uploader, string $directory)
    {
        $this->uploader = $uploader;
        $this->directory = $directory;
    }

    /**
     * @ORM\PrePersist
     * @ORM\PreUpdate
     */
    public function prePersistHandler(CadrageFile $cadrageFile)
    {
        if (!empty($cadrageFile->getFile())) {
            $extension = $this->uploader->getExtension($cadrageFile->getFile());

            if (empty($cadrageFile->getFileName())) {
                $cadrageFile->setFileName(md5(uniqid()));
            }
            if (empty($cadrageFile->getTitle())) {
                $cadrageFile->setTitle('Nouveau fichier');
            }
            $cadrageFile->setFileExtension($extension);
        }
        $cadrageFile->setUpdateAt(new \DateTime());
    }

    /**
     * @ORM\PostPersist()
     * @ORM\PostUpdate()
     */
    public function postPersistHandler(CadrageFile $cadrageFile)
    {
        if (!empty($cadrageFile->getFile())) {
            $fileDirectory = new FileDirectory();

            $fileDirectory->createDir(
                $this->directory,
                $cadrageFile->getAction()->getId());

            $fileDirectory->createDir(
                $this->directory.'/'.$cadrageFile->getAction()->getId(),
                'cadrage');

            $targetDir = $this->directory.'/'.
                $cadrageFile->getAction()->getId().'/'.
                'cadrage';

            if (null !== $cadrageFile->getFullName()) {
                $fileDirectory->removeFile($targetDir, $cadrageFile->getFullName());
            }

            $this->uploader->setTargetDir($targetDir);
            $this->uploader->upload($cadrageFile->getFile(), $cadrageFile->getFileName());
        }
    }

    /**
     * @ORM\PostRemove()
     */
    public function postRemoveHandler(CadrageFile $cadrageFile)
    {
        $fileDirectory = new FileDirectory();

        $targetDir = $this->directory.'/'.
            $cadrageFile->getAction()->getId().'/cadrage';

        $fileDirectory->removeFile($targetDir, $cadrageFile->getFullName());
    }
}
