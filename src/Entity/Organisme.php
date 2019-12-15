<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\OrganismeRepository")
 */
class Organisme implements EntityInterface
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
     * @ORM\Column(type="string", length=5)
     */
    private $ref;

    /**
     * @ORM\Column(type="boolean")
     */
    private $alterable;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $content;

    /**
     * @ORM\Column(type="boolean")
     */
    private $enable;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\User", inversedBy="organismes")
     * @ORM\OrderBy({"name" = "ASC"})
     */
    private $users;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Corbeille", mappedBy="organisme", orphanRemoval=true)
     */
    private $corbeilles;

    public function __construct()
    {
        $this->users = new ArrayCollection();
        $this->corbeilles = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(int $id): self
    {
         $this->id=$id;

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

    public function getRef(): ?string
    {
        return $this->ref;
    }

    public function setRef(string $ref): self
    {
        $this->ref = $ref;

        return $this;
    }

    public function getAlterable(): ?bool
    {
        return $this->alterable;
    }

    public function setAlterable(bool $alterable): self
    {
        $this->alterable = $alterable;

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

    public function getEnable(): ?bool
    {
        return $this->enable;
    }

    public function setEnable(bool $enable): self
    {
        $this->enable = $enable;

        return $this;
    }

    public function getFullName(): ?string
    {
        return $this->getRef() . ' - ' . $this->getName();
    }

    /**
     * @return Collection|User[]
     */
    public function getUsers(): Collection
    {
        return $this->users;
    }

    public function addUser(User $user): self
    {
        if (!$this->users->contains($user)) {
            $this->users[] = $user;
        }

        return $this;
    }

    public function removeUser(User $user): self
    {
        if ($this->users->contains($user)) {
            $this->users->removeElement($user);
        }

        return $this;
    }

    /**
     * @return Collection|Corbeille[]
     */
    public function getCorbeilles(): Collection
    {
        return $this->corbeilles;
    }

    public function addCorbeille(Corbeille $corbeille): self
    {
        if (!$this->corbeilles->contains($corbeille)) {
            $this->corbeilles[] = $corbeille;
            $corbeille->setOrganisme($this);
        }

        return $this;
    }

    public function removeCorbeille(Corbeille $corbeille): self
    {
        if ($this->corbeilles->contains($corbeille)) {
            $this->corbeilles->removeElement($corbeille);
            // set the owning side to null (unless already changed)
            if ($corbeille->getOrganisme() === $this) {
                $corbeille->setOrganisme(null);
            }
        }

        return $this;
    }

}
