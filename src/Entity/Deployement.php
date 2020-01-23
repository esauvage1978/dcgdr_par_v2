<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
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

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\IndicatorValue", mappedBy="deployement", orphanRemoval=true)
     */
    private $indicatorValues;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Corbeille", inversedBy="deployementWriters")
     * @ORM\JoinTable("deployementwriter_corbeille")
     */
    private $writers;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Corbeille", inversedBy="deployementReaders")
     * @ORM\JoinTable("deployementreader_corbeille")
     */
    private $readers;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\DeployementLink", mappedBy="deployement",cascade={"persist"})
     */
    private $deployementLinks;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\DeployementFile", mappedBy="deployement", orphanRemoval=true,cascade={"persist"})
     */
    private $deployementFiles;

    public function __construct()
    {
        $this->setTaux1('0');
        $this->setTaux2('0');
        $this->indicatorValues = new ArrayCollection();
        $this->writers = new ArrayCollection();
        $this->readers = new ArrayCollection();
        $this->deployementLinks = new ArrayCollection();
        $this->deployementFiles = new ArrayCollection();
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
            $indicatorValue->setDeployement($this);
        }

        return $this;
    }

    public function removeIndicatorValue(IndicatorValue $indicatorValue): self
    {
        if ($this->indicatorValues->contains($indicatorValue)) {
            $this->indicatorValues->removeElement($indicatorValue);
            // set the owning side to null (unless already changed)
            if ($indicatorValue->getDeployement() === $this) {
                $indicatorValue->setDeployement(null);
            }
        }

        return $this;
    }

    public function isEnded(): bool
    {
        return empty($this->getEndAt());
    }

    /**
     * @return Collection|Corbeille[]
     */
    public function getWriters(): Collection
    {
        return $this->writers;
    }

    public function addWriter(Corbeille $writer): self
    {
        if (!$this->writers->contains($writer)) {
            $this->writers[] = $writer;
        }

        return $this;
    }

    public function removeWriter(Corbeille $writer): self
    {
        if ($this->writers->contains($writer)) {
            $this->writers->removeElement($writer);
        }

        return $this;
    }


    /**
     * @return Collection|Corbeille[]
     */
    public function getReaders(): Collection
    {
        return $this->readers;
    }

    public function addReader(Corbeille $reader): self
    {
        if (!$this->readers->contains($reader)) {
            $this->readers[] = $reader;
        }

        return $this;
    }

    public function removeReader(Corbeille $reader): self
    {
        if ($this->readers->contains($reader)) {
            $this->readers->removeElement($reader);
        }

        return $this;
    }

    /**
     * @return Collection|DeployementLink[]
     */
    public function getDeployementLinks(): Collection
    {
        return $this->deployementLinks;
    }

    public function addDeployementLink(DeployementLink $deployementLink): self
    {
        if (!$this->deployementLinks->contains($deployementLink)) {
            $this->deployementLinks[] = $deployementLink;
            $deployementLink->setDeployement($this);
        }

        return $this;
    }

    public function removeDeployementLink(DeployementLink $deployementLink): self
    {
        if ($this->deployementLinks->contains($deployementLink)) {
            $this->deployementLinks->removeElement($deployementLink);
            // set the owning side to null (unless already changed)
            if ($deployementLink->getDeployement() === $this) {
                $deployementLink->setDeployement(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|DeployementFile[]
     */
    public function getDeployementFiles(): Collection
    {
        return $this->deployementFiles;
    }

    public function addDeployementFile(DeployementFile $deployementFile): self
    {
        if (!$this->deployementFiles->contains($deployementFile)) {
            $this->deployementFiles[] = $deployementFile;
            $deployementFile->setDeployement($this);
        }

        return $this;
    }

    public function removeDeployementFile(DeployementFile $deployementFile): self
    {
        if ($this->deployementFiles->contains($deployementFile)) {
            $this->deployementFiles->removeElement($deployementFile);
            // set the owning side to null (unless already changed)
            if ($deployementFile->getDeployement() === $this) {
                $deployementFile->setDeployement(null);
            }
        }

        return $this;
    }
}
