<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\AxeRepository")
 */
class Axe implements EntityInterface
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=30)
     */
    private $name;

    /**
     * @ORM\Column(type="boolean")
     */
    private $enable;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $content;

    /**
     * @ORM\Column(type="integer")
     */
    private $taux1;

    /**
     * @ORM\Column(type="integer")
     */
    private $taux2;

    /**
     * @ORM\Column(type="boolean")
     */
    private $archiving;


    public function __construct()
    {
        $this->setTaux1('0');
        $this->setTaux2('0');
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(int $id): self
    {
        $this->id = $id;

        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getEnable(): ?bool
    {
        return $this->enable;
    }

    public function setEnable(bool $enable): self
    {
        $this->enable = $enable;

        return $this;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(?string $content): self
    {
        $this->content = $content;

        return $this;
    }

    public function getTaux1(): ?int
    {
        return $this->taux1;
    }

    public function setTaux1(int $taux1): self
    {
        $this->taux1 = $taux1;

        return $this;
    }

    public function getTaux2(): ?int
    {
        return $this->taux2;
    }

    public function setTaux2(int $taux2): self
    {
        $this->taux2 = $taux2;

        return $this;
    }

    public function getArchiving(): ?bool
    {
        return $this->archiving;
    }

    public function setArchiving(bool $archiving): self
    {
        $this->archiving = $archiving;

        return $this;
    }

}
