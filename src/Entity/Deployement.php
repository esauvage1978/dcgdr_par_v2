<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\DeployementRepository")
 */
class Deployement implements EntityInterface
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="integer")
     */
    private $taux1;

    /**
     * @ORM\Column(type="integer")
     */
    private $taux2;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Organisme", inversedBy="deployements")
     * @ORM\JoinColumn(nullable=false)
     */
    private $organisme;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Action", inversedBy="deployements")
     * @ORM\JoinColumn(nullable=false)
     */
    private $action;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $showAt;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $endAt;

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

    public function getOrganisme(): ?Organisme
    {
        return $this->organisme;
    }

    public function setOrganisme(?Organisme $organisme): self
    {
        $this->organisme = $organisme;

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

    public function getShowAt(): ?\DateTimeInterface
    {
        return $this->showAt;
    }

    public function setShowAt(?\DateTimeInterface $showAt): self
    {
        $this->showAt = $showAt;

        return $this;
    }

    public function getEndAt(): ?\DateTimeInterface
    {
        return $this->endAt;
    }

    public function setEndAt(?\DateTimeInterface $endAt): self
    {
        $this->endAt = $endAt;

        return $this;
    }
}
