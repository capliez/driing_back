<?php

namespace App\Entity;

use App\Repository\LogsRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=LogsRepository::class)
 */
class Logs
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $nameEntity;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $action;

    /**
     * @ORM\Column(type="json", nullable=true)
     */
    private $prevData = [];

    /**
     * @ORM\Column(type="json")
     */
    private $nextState = [];

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="logs")
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNameEntity(): ?string
    {
        return $this->nameEntity;
    }

    public function setNameEntity(string $nameEntity): self
    {
        $this->nameEntity = $nameEntity;

        return $this;
    }

    public function getAction(): ?string
    {
        return $this->action;
    }

    public function setAction(string $action): self
    {
        $this->action = $action;

        return $this;
    }

    public function getPrevData(): ?array
    {
        return $this->prevData;
    }

    public function setPrevData(?array $prevData): self
    {
        $this->prevData = $prevData;

        return $this;
    }

    public function getNextState(): ?array
    {
        return $this->nextState;
    }

    public function setNextState(array $nextState): self
    {
        $this->nextState = $nextState;

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
