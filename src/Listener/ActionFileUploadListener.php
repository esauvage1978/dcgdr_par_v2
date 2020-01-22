<?php

namespace App\Listener;

use App\Entity\ActionFile;
use App\Helper\FileDirectory;
use App\Service\Uploader;
use Doctrine\ORM\Mapping as ORM;

class ActionFileUploadListener
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
    public function prePersistHandler(ActionFile $actionFile)
    {
        if (!empty($actionFile->getFile())) {
            $extension = $this->uploader->getExtension($actionFile->getFile());

            if (empty($actionFile->getFileName())) {
                $actionFile->setFileName(md5(uniqid()));
            }
            if (empty($actionFile->getTitle())) {
                $actionFile->setTitle('Nouveau fichier');
            }
            $actionFile->setFileExtension($extension);
        }
        $actionFile->setUpdateAt(new \DateTime());
    }

    /**
     * @ORM\PostPersist()
     * @ORM\PostUpdate()
     */
    public function postPersistHandler(ActionFile $actionFile)
    {
        if (!empty($actionFile->getFile())) {
            $fileDirectory = new FileDirectory();

            $fileDirectory->createDir($this->directory, $actionFile->getAction()->getId());
            $targetDir = $this->directory.'/'.$actionFile->getAction()->getId();

            if (null !== $actionFile->getFullName()) {
                $fileDirectory->removeFile($targetDir, $actionFile->getFullName());
            }

            $this->uploader->setTargetDir($targetDir);
            $this->uploader->upload($actionFile->getFile(), $actionFile->getFileName());
        }
    }

    /**
     * @ORM\PostRemove()
     */
    public function postRemoveHandler(ActionFile $actionFile)
    {
        $fileDirectory = new FileDirectory();
        $targetDir = $this->directory.'/'.$actionFile->getAction()->getId();
        $fileDirectory->removeFile($targetDir, $actionFile->getFullName());
    }
}
