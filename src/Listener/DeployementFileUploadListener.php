<?php

namespace App\Listener;

use App\Entity\ActionFile;
use App\Entity\DeployementFile;
use App\Helper\FileDirectory;
use App\Service\Uploader;
use Doctrine\ORM\Mapping as ORM;

class DeployementFileUploadListener
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
    public function prePersistHandler(DeployementFile $deployementFile)
    {
        if (!empty($deployementFile->getFile())) {
            $extension = $this->uploader->getExtension($deployementFile->getFile());

            if (empty($deployementFile->getFileName())) {
                $deployementFile->setFileName(md5(uniqid()));
            }
            if (empty($deployementFile->getTitle())) {
                $deployementFile->setTitle('Nouveau fichier');
            }
            $deployementFile->setFileExtension($extension);
        }
        $deployementFile->setUpdateAt(new \DateTime());
    }

    /**
     * @ORM\PostPersist()
     * @ORM\PostUpdate()
     */
    public function postPersistHandler(DeployementFile $deployementFile)
    {
        if (!empty($deployementFile->getFile())) {
            $fileDirectory = new FileDirectory();

            $fileDirectory->createDir(
                $this->directory,
                $deployementFile->getDeployement()->getAction()->getId());

            $fileDirectory->createDir(
                $this->directory. '/' . $deployementFile->getDeployement()->getAction()->getId(),
                $deployementFile->getDeployement()->getId());

            $targetDir = $this->directory.'/'.
                $deployementFile->getDeployement()->getAction()->getId().'/'.
                $deployementFile->getDeployement()->getId();

            if (null !== $deployementFile->getFullName()) {
                $fileDirectory->removeFile($targetDir, $deployementFile->getFullName());
            }

            $this->uploader->setTargetDir($targetDir);
            $this->uploader->upload($deployementFile->getFile(), $deployementFile->getFileName());
        }
    }

    /**
     * @ORM\PostRemove()
     */
    public function postRemoveHandler(DeployementFile $deployementFile)
    {
        $fileDirectory = new FileDirectory();

        $targetDir = $this->directory.'/'.
            $deployementFile->getDeployement()->getAction()->getId().'/'.
            $deployementFile->getDeployement()->getId();

        $fileDirectory->removeFile($targetDir, $deployementFile->getFullName());
    }
}
