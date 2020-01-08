<?php

namespace App\Entity;

use App\Workflow\WorkflowData;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ActionRepository")
 */
class Action implements EntityInterface
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
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $regionStartAt;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $regionEndAt;

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
    private $experimental;

    /**
     * @ORM\Column(type="boolean")
     */
    private $showAll;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $cadrage;

    /**
     * @ORM\Column(type="string", length=10)
     */
    private $ref;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $measureValue;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $measureContent;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Category", inversedBy="actions")
     * @ORM\JoinColumn(nullable=false)
     */
    private $category;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Cible", inversedBy="actions")
     */
    private $cibles;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Vecteur", inversedBy="actions")
     */
    private $vecteurs;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Corbeille", inversedBy="actionReaders")
     * @ORM\JoinTable("actionreader_corbeille")
     */
    private $readers;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Corbeille", inversedBy="actionWriters")
     * @ORM\JoinTable("actionwriter_corbeille")
     */
    private $writers;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Corbeille", inversedBy="actionValiders")
     * @ORM\JoinTable("actionvalider_corbeille")
     */
    private $validers;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Deployement", mappedBy="action", orphanRemoval=true)
     */
    private $deployements;

    public function __construct()
    {
        $this->setTaux1('0');
        $this->setTaux2('0');
        $this->setShowAll(true);
        $this->setExperimental(false);
        $this->cibles = new ArrayCollection();
        $this->vecteurs = new ArrayCollection();
        $this->readers = new ArrayCollection();
        $this->writers = new ArrayCollection();
        $this->validers = new ArrayCollection();
        $this->deployements = new ArrayCollection();
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

    public function getRegionStartAt(): ?\DateTimeInterface
    {
        return $this->regionStartAt;
    }

    public function setRegionStartAt(?\DateTimeInterface $regionStartAt): self
    {
        $this->regionStartAt = $regionStartAt;

        return $this;
    }

    public function getRegionEndAt(): ?\DateTimeInterface
    {
        return $this->regionEndAt;
    }

    public function setRegionEndAt(?\DateTimeInterface $regionEndAt): self
    {
        $this->regionEndAt = $regionEndAt;

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

    public function getExperimental(): ?bool
    {
        return $this->experimental;
    }

    public function setExperimental(bool $experimental): self
    {
        $this->experimental = $experimental;

        return $this;
    }

    public function getShowAll(): ?bool
    {
        return $this->showAll;
    }

    public function setShowAll(bool $showAll): self
    {
        $this->showAll = $showAll;

        return $this;
    }

    public function getCadrage(): ?string
    {
        return $this->cadrage;
    }

    public function setCadrage(?string $cadrage): self
    {
        $this->cadrage = $cadrage;

        return $this;
    }

    public function getRef(): ?string
    {
        return $this->ref;
    }

    public function setRef(string $ref): self
    {
        $this->ref = $ref;

        return $this;
    }

    public function getFullName(): ?string
    {
        return $this->getRef().' - '.$this->getName();
    }

    public function getMeasureValue(): ?int
    {
        return $this->measureValue;
    }

    public function setMeasureValue(?int $measureValue): self
    {
        $this->measureValue = $measureValue;

        return $this;
    }

    public function getMeasureContent(): ?string
    {
        return $this->measureContent;
    }

    public function setMeasureContent(?string $measureContent): self
    {
        $this->measureContent = $measureContent;

        return $this;
    }

    public function getCategory(): ?Category
    {
        return $this->category;
    }

    public function setCategory(?Category $category): self
    {
        $this->category = $category;

        return $this;
    }

    /**
     * @return Collection|Cible[]
     */
    public function getCibles(): Collection
    {
        return $this->cibles;
    }

    public function addCible(Cible $cible): self
    {
        if (!$this->cibles->contains($cible)) {
            $this->cibles[] = $cible;
        }

        return $this;
    }

    public function removeCible(Cible $cible): self
    {
        if ($this->cibles->contains($cible)) {
            $this->cibles->removeElement($cible);
        }

        return $this;
    }

    /**
     * @return Collection|Vecteur[]
     */
    public function getVecteurs(): Collection
    {
        return $this->vecteurs;
    }

    public function addVecteur(Vecteur $vecteur): self
    {
        if (!$this->vecteurs->contains($vecteur)) {
            $this->vecteurs[] = $vecteur;
        }

        return $this;
    }

    public function removeVecteur(Vecteur $vecteur): self
    {
        if ($this->vecteurs->contains($vecteur)) {
            $this->vecteurs->removeElement($vecteur);
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
    public function getValiders(): Collection
    {
        return $this->validers;
    }

    public function addValider(Corbeille $valider): self
    {
        if (!$this->validers->contains($valider)) {
            $this->validers[] = $valider;
        }

        return $this;
    }

    public function removeValider(Corbeille $valider): self
    {
        if ($this->validers->contains($valider)) {
            $this->validers->removeElement($valider);
        }

        return $this;
    }

    /**
     * @return Collection|Deployement[]
     */
    public function getDeployements(): Collection
    {
        return $this->deployements;
    }

    public function addDeployement(Deployement $deployement): self
    {
        if (!$this->deployements->contains($deployement)) {
            $this->deployements[] = $deployement;
            $deployement->setAction($this);
        }

        return $this;
    }

    public function removeDeployement(Deployement $deployement): self
    {
        if ($this->deployements->contains($deployement)) {
            $this->deployements->removeElement($deployement);
            // set the owning side to null (unless already changed)
            if ($deployement->getAction() === $this) {
                $deployement->setAction(null);
            }
        }

        return $this;
    }
}
