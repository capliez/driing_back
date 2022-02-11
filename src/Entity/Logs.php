<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\LogsRepository;

/**
 * @ORM\Entity(repositoryClass=LogsRepository::class)
 * @ORM\Table(name="logs")
 * @ORM\HasLifecycleCallbacks
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
    private $message;




    public function getId(): ?int
    {
        return $this->id;
    }

    public function getMessage(): ?string
    {
        return $this->message;
    }

    public function setMessage(string $message): self
    {
        $this->message = $message;

        return $this;
    }



}
