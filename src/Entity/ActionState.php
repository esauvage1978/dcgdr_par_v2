<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ActionStateRepository")
 */
class ActionState implements EntityInterface
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Action", inversedBy="actionStates")
     * @ORM\JoinColumn(nullable=false)
     */
    private $action;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="actionStates")
     */
    private $user;

    /**
     * @ORM\Column(type="string", length=20)
     */
    private $stateOld;

    /**
     * @ORM\Column(type="string", length=20)
     */
    private $stateNew;

    /**
     * @ORM\Column(type="datetime")
     */
    private $changeAt;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $content;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(int $id): self
    {
        $this->id = $id;

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

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getStateOld(): ?string
    {
        return $this->stateOld;
    }

    public function setStateOld(string $stateOld): self
    {
        $this->stateOld = $stateOld;

        return $this;
    }

    public function getStateNew(): ?string
    {
        return $this->stateNew;
    }

    public function setStateNew(string $stateNew): self
    {
        $this->stateNew = $stateNew;

        return $this;
    }

    public function getChangeAt(): ?\DateTimeInterface
    {
        return $this->changeAt;
    }

    public function setChangeAt(\DateTimeInterface $changeAt): self
    {
        $this->changeAt = $changeAt;

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
}
