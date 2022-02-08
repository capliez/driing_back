<?php

namespace App\Entity\Traits;

use DateTime;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * Trait TimeStampableTrait
 * @package App\Entity\Trait
 */
trait TimeStampableTrait
{
    /**
     * @var DateTime
     * @Gedmo\Timestampable(on="update")
     * @ORM\Column(type="datetime")
     * @Assert\Type(
     *     type="\DateTime",
     *     message="typeError.dateTime"
     * )
     * @Groups({"users_read"})
     */
    private $updatedAt;

    /**
     * @var DateTime
     * @Gedmo\Timestampable(on="create")
     * @ORM\Column(type="datetime")
     * @Assert\Type(
     *     type="\DateTime",
     *     message="typeError.dateTime"
     * )
     * @Groups({"users_read"})
     */
    private $createdAt;

    /**
     * @return \DateTimeInterface|null
     */
    public function getUpdatedAt(): \DateTimeInterface
    {
        return $this->updatedAt;
    }

    /**
     * @return \DateTimeInterface|null
     */
    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

}