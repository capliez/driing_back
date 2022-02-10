<?php

namespace App\Entity;

use App\Entity\Traits\TimeStampableTrait;
use App\Repository\ResidentRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;
use ApiPlatform\Core\Annotation\ApiResource;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=ResidentRepository::class)
 * @UniqueEntity(
 *     fields={"phone"},
 *     message="resident.unique"
 * )
 * @ApiResource(
 *     collectionOperations={"GET", "POST"},
 *     itemOperations={"GET", "PUT"},
 *     normalizationContext={
 *          "groups"={"residents_read"}
 *     },
 *     denormalizationContext={
 *          "disable_type_enforcement"=true
 *     }
 * )
 */
class Resident
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups({"residents_read"})
     */
    private $id;

    /**
     * @var string
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message="resident.fields.lastName.constraints.notBlank")
     * @Assert\Regex(pattern="/\d/", match=false, message="resident.fields.lastName.constraints.regex")
     * @Groups({"residents_read"})
     * @Assert\Type(
     *     type="string",
     *     message="typeError.string"
     * )
     */
    private $lastName;

    /**
     * @var string
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message="resident.fields.phoneCompany.constraints.notBlank")
     * @Groups({"residents_read"})
     * @Assert\Type(
     *     type="string",
     *     message="typeError.string"
     * )
     */
    private $phone;

    /**
     * @var string
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups({"residents_read"})
     * @Assert\Type(
     *     type="string",
     *     message="typeError.string"
     * )
     */
    private $email;

    /**
     * @ORM\ManyToOne(targetEntity=Building::class, inversedBy="residents")
     * @ORM\JoinColumn(nullable=false)
     * @Assert\NotBlank(message="resident.fields.building.constraints.notBlank")
     * @Assert\Type(
     *     type="object",
     *     message="typeError.object"
     * )
     */
    private $building;

    use TimeStampableTrait;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    public function setLastName(string $lastName): self
    {
        $this->lastName = $lastName;

        return $this;
    }

    public function getPhone(): ?string
    {
        return $this->phone;
    }

    public function setPhone(string $phone): self
    {
        $this->phone = $phone;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(?string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getBuilding(): ?Building
    {
        return $this->building;
    }

    public function setBuilding(?Building $building): self
    {
        $this->building = $building;

        return $this;
    }
}
