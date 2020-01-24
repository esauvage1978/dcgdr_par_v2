<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\UploadedFile;

/**
 * @ORM\Entity(repositoryClass="App\Repository\CadrageFileRepository")
 * @ORM\EntityListeners({"App\Listener\CadrageFileUploadListener"})
 */
class CadrageFile implements EntityInterface
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $fileName;

    private $file;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $updateAt;

    /**
     * @ORM\Column(type="string", length=10)
     */
    private $fileExtension;

    /**
     * @ORM\Column(type="integer")
     */
    private $nbrView;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Action", inversedBy="cadrageFiles")
     * @ORM\JoinColumn(nullable=false)
     */
    private $action;

    /**
     * @ORM\Column(type="string", length=50)
     */
    private $title;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $comment;

    public function __construct()
    {
        $this->setNbrView(0);
    }


    /**
     * @return string
     */
    public function getFullName(): ?string
    {
        return $this->fileName.'.'.$this->fileExtension;
    }

    /**
     * @return string
     */
    public function getUploadDir(): string
    {
        return 'uploads/actions/' . $this->getAction()->getId() .'/cadrage/';
    }

    public function getHref(): string
    {
        return $this->getUploadDir() .  '/' . $this->getFileName() . '.' .  $this->getFileExtension() ;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFileName(): ?string
    {
        return $this->fileName;
    }

    public function setFileName(string $fileName): self
    {
        $this->fileName = $fileName;

        return $this;
    }

    public function getUpdateAt(): ?\DateTimeInterface
    {
        return $this->updateAt;
    }

    public function setUpdateAt(?\DateTimeInterface $updateAt): self
    {
        $this->updateAt = $updateAt;

        return $this;
    }

    public function getFileExtension(): ?string
    {
        return $this->fileExtension;
    }

    public function setFileExtension(string $fileExtension): self
    {
        $this->fileExtension = $fileExtension;

        return $this;
    }

    public function getNbrView(): ?int
    {
        return $this->nbrView;
    }

    public function setNbrView(int $nbrView): self
    {
        $this->nbrView = $nbrView;

        return $this;
    }

    public function getAction(): ?Action
    {
        return $this->action;
    }

    public function setAction(?Action $action): self
    {
        $this->action = $action;

        return $this;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getFile()
    {
        return $this->file;
    }

    public function setFile(UploadedFile $actionsFile): self
    {
        $this->file = $actionsFile;

        return $this;
    }

    public function getComment(): ?string
    {
        return $this->comment;
    }

    public function setComment(?string $comment): self
    {
        $this->comment = $comment;

        return $this;
    }
}
