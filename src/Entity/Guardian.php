<?php

namespace App\Entity;

use App\Repository\GuardianRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;
use App\Entity\Traits\TimeStampableTrait;
use ApiPlatform\Core\Annotation\ApiResource;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=GuardianRepository::class)
 * @UniqueEntity(
 *     fields={"phone"},
 *     message="guardian.unique"
 * )
 * @ApiResource(
 *     collectionOperations={"GET"},
 *     itemOperations={"GET", "PUT"},
 *     normalizationContext={
 *          "groups"={"guardians_read"}
 *     },
 *     denormalizationContext={
 *          "disable_type_enforcement"=true
 *     }
 * )
 */
class Guardian
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups({"guardians_read", "users_read"})
     */
    private $id;

    /**
     * @var string
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message="guardian.fields.firstName.constraints.notBlank")
     * @Assert\Regex(pattern="/\d/", match=false, message="guardian.fields.firstName.constraints.regex")
     * @Groups({"guardians_read", "users_read"})
     * @Assert\Type(
     *     type="string",
     *     message="typeError.string"
     * )
     * @Groups({"guardians_read", "users_read"})
     */
    private $firstName;

    /**
     * @var string
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message="guardian.fields.lastName.constraints.notBlank")
     * @Assert\Regex(pattern="/\d/", match=false, message="guardian.fields.lastName.constraints.regex")
     * @Groups({"guardians_read", "users_read"})
     * @Assert\Type(
     *     type="string",
     *     message="typeError.string"
     * )
     */
    private $lastName;

    /**
     * @var string
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message="guardian.fields.phoneCompany.constraints.notBlank")
     * @Groups({"guardians_read", "users_read"})
     * @Assert\Type(
     *     type="string",
     *     message="typeError.string"
     * )
     */
    private $phone;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     * @Groups({"guardians_read", "users_read"})
     * @Assert\Type(
     *     type="bool",
     *     message="typeError.bool"
     * )
     */
    private $isOnboarding;

    /**
     * @ORM\OneToOne(targetEntity=User::class, inversedBy="guardian", cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=false)
     * @Groups({"guardians_read"})
     * @Assert\Type(
     *     type="object",
     *     message="typeError.object"
     * )
     */
    private $user;

    use TimeStampableTrait;

    /**
     * @Groups({"guardians_read", "users_read"})
     * @return string
     */
    public function getFullName(): string
    {
        return $this->firstName.' '.$this->lastName;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    public function setFirstName(string $firstName): self
    {
        $this->firstName = $firstName;

        return $this;
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

    public function getIsOnboarding(): ?bool
    {
        return $this->isOnboarding;
    }

    public function setIsOnboarding(?bool $isOnboarding): self
    {
        $this->isOnboarding = $isOnboarding;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(User $user): self
    {
        $this->user = $user;

        return $this;
    }


}
