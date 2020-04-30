<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\IndicatorValueRepository")
 * @ORM\EntityListeners({"App\Listener\IndicatorValueListener"})
 */
class IndicatorValue implements EntityInterface
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

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
     * @ORM\ManyToOne(targetEntity="App\Entity\Indicator", inversedBy="indicatorValues")
     * @ORM\JoinColumn(nullable=false)
     */
    private $indicator;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Deployement", inversedBy="indicatorValues")
     * @ORM\JoinColumn(nullable=false)
     */
    private $deployement;

    /**
     * @ORM\Column(type="string", length=20, nullable=true)
     */
    private $goal;

    /**
     * @ORM\Column(type="string", length=20, nullable=true)
     */
    private $value;

    /**
     * @ORM\Column(type="boolean")
     */
    private $enable;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\IndicatorValueHistory", mappedBy="indicatorValue", orphanRemoval=true)
     */
    private $indicatorValueHistories;

    public function __construct()
    {
        $this->setTaux1('0');
        $this->setTaux2('0');
        $this->indicatorValueHistories = new ArrayCollection();
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

    public function getIndicator(): ?Indicator
    {
        return $this->indicator;
    }

    public function setIndicator(?Indicator $indicator): self
    {
        $this->indicator = $indicator;

        return $this;
    }

    public function getDeployement(): ?Deployement
    {
        return $this->deployement;
    }

    public function setDeployement(?Deployement $deployement): self
    {
        $this->deployement = $deployement;

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

    public function setValue(?string $value): self
    {
        $this->value = $value;

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

    /**
     * @return Collection|IndicatorValueHistory[]
     */
    public function getIndicatorValueHistories(): Collection
    {
        return $this->indicatorValueHistories;
    }

    public function addIndicatorValueHistory(IndicatorValueHistory $indicatorValueHistory): self
    {
        if (!$this->indicatorValueHistories->contains($indicatorValueHistory)) {
            $this->indicatorValueHistories[] = $indicatorValueHistory;
            $indicatorValueHistory->setIndicatorValue($this);
        }

        return $this;
    }

    public function removeIndicatorValueHistory(IndicatorValueHistory $indicatorValueHistory): self
    {
        if ($this->indicatorValueHistories->contains($indicatorValueHistory)) {
            $this->indicatorValueHistories->removeElement($indicatorValueHistory);
            // set the owning side to null (unless already changed)
            if ($indicatorValueHistory->getIndicatorValue() === $this) {
                $indicatorValueHistory->setIndicatorValue(null);
            }
        }

        return $this;
    }
}
