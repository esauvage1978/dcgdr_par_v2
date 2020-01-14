<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\IndicatorValueHistoryRepository")
 */
class IndicatorValueHistory implements EntityInterface
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="datetime")
     */
    private $addedAt;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\IndicatorValue", inversedBy="indicatorValueHistories")
     * @ORM\JoinColumn(nullable=false)
     */
    private $indicatorValue;



    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $content;

    /**
     * @ORM\Column(type="string", length=10, nullable=true)
     */
    private $goal;

    /**
     * @ORM\Column(type="string", length=10, nullable=true)
     */
    private $value;

    /**
     * @ORM\Column(type="integer")
     */
    private $taux1;

    /**
     * @ORM\Column(type="integer")
     */
    private $taux2;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="indicatorValueHistories")
     */
    private $user;

    public function __construct()
    {
        $this->setTaux1(0);
        $this->setTaux2(0);
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

    public function getAddedAt(): ?\DateTimeInterface
    {
        return $this->addedAt;
    }

    public function setAddedAt(\DateTimeInterface $addedAt): self
    {
        $this->addedAt = $addedAt;

        return $this;
    }

    public function getIndicatorValue(): ?IndicatorValue
    {
        return $this->indicatorValue;
    }

    public function setIndicatorValue(?IndicatorValue $indicatorValue): self
    {
        $this->indicatorValue = $indicatorValue;

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

    public function getGoal(): ?string
    {
        return $this->goal;
    }

    public function setGoal(?string $goal): self
    {
        $this->goal = $goal;

        return $this;
    }

    public function getValue(): ?string
    {
        return $this->value;
    }

    public function setValue(string $value): self
    {
        $this->value = $value;

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

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }
}
