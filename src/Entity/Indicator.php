<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\IndicatorRepository")
 * @ORM\EntityListeners({"App\Listener\IndicatorListener"})
 */
class Indicator implements EntityInterface
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
     * @ORM\Column(type="string", length=20, nullable=true)
     */
    private $indicatorType;

    /**
     * @ORM\Column(type="string", length=20, nullable=true)
     */
    private $goal;

    /**
     * @ORM\Column(type="string", length=20, nullable=true)
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
     * @ORM\ManyToOne(targetEntity="App\Entity\Action", inversedBy="indicators")
     * @ORM\JoinColumn(nullable=false)
     */
    private $action;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\IndicatorValue", mappedBy="indicator", orphanRemoval=true)
     */
    private $indicatorValues;

    public function __construct()
    {
        $this->setTaux1('0');
        $this->setTaux2('0');
        $this->indicatorValues = new ArrayCollection();
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

    public function getIndicatorType(): ?string
    {
        return $this->indicatorType;
    }

    public function setIndicatorType(?string $indicatorType): self
    {
        $this->indicatorType = $indicatorType;

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

    public function getAction(): ?Action
    {
        return $this->action;
    }

    public function setAction(?Action $action): self
    {
        $this->action = $action;

        return $this;
    }

    /**
     * @return Collection|IndicatorValue[]
     */
    public function getIndicatorValues(): Collection
    {
        return $this->indicatorValues;
    }

    public function addIndicatorValue(IndicatorValue $indicatorValue): self
    {
        if (!$this->indicatorValues->contains($indicatorValue)) {
            $this->indicatorValues[] = $indicatorValue;
            $indicatorValue->setIndicator($this);
        }

        return $this;
    }

    public function removeIndicatorValue(IndicatorValue $indicatorValue): self
    {
        if ($this->indicatorValues->contains($indicatorValue)) {
            $this->indicatorValues->removeElement($indicatorValue);
            // set the owning side to null (unless already changed)
            if ($indicatorValue->getIndicator() === $this) {
                $indicatorValue->setIndicator(null);
            }
        }

        return $this;
    }
}
