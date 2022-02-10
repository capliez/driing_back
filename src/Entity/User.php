<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Scheb\TwoFactorBundle\Model\Email\TwoFactorInterface;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use App\Entity\Traits\TimeStampableTrait;
use Symfony\Component\Validator\Constraints as Assert;
use DateTimeImmutable;
use ApiPlatform\Core\Annotation\ApiResource;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Serializer\Annotation\Groups;
use Gedmo\Mapping\Annotation as Gedmo;
use App\Validator as AcmeAssert;

/**
 * @ORM\Entity(repositoryClass=UserRepository::class)
 * @ORM\Table(name="`user`")
 * @UniqueEntity(
 *     fields={"phone"},
 *     message="user.unique"
 * )
 * @AcmeAssert\ContainsUserEmail(message="user.fields.email.constraints.unique")
 * @ApiResource(
 *     collectionOperations={"GET"},
 *     itemOperations={"GET", "PUT"},
 *     normalizationContext={
 *          "groups"={"users_read"}
 *     },
 *     denormalizationContext={
 *          "disable_type_enforcement"=true
 *     }
 * )
 */
class User implements UserInterface, PasswordAuthenticatedUserInterface, TwoFactorInterface
{

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups({"users_read"})
     */
    private $id;

    /**
     * @var string
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message="user.fields.firstName.constraints.notBlank")
     * @Assert\Regex(pattern="/\d/", match=false, message="user.fields.firstName.constraints.regex")
     * @Groups({"users_read"})
     * @Assert\Type(
     *     type="string",
     *     message="typeError.string"
     * )
     */
    private $firstName;

    /**
     * @var string
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message="user.fields.lastName.constraints.notBlank")
     * @Assert\Regex(pattern="/\d/", match=false, message="user.fields.lastName.constraints.regex")
     * @Groups({"users_read"})
     * @Assert\Type(
     *     type="string",
     *     message="typeError.string"
     * )
     */
    private $lastName;

    /**
     * @var string
     * @ORM\Column(type="string", length=180, unique=true, nullable=true)
     * @Assert\Email(message="user.fields.email.constraints.valid")
     * @Groups({"users_read"})
     * @Assert\Type(
     *     type="string",
     *     message="typeError.string"
     * )
     */
    private $email;

    /**
     * @var string The hashed password
     * @Assert\NotBlank(message="user.fields.password.constraints.notBlank", groups={"registration"})
     * @ORM\Column(type="string")
     * @Assert\Regex(pattern="/^(?=.*[!@#$%^&*-])(?=.*[0-9])(?=.*[A-Z]).{8,50}$/", match=true, message="user.fields.password.constraints.regex", groups={"registration"})
     * @Assert\Type(
     *     type="string",
     *     message="typeError.string"
     * )
     */
    private $password;

    /**
     * @var string
     * @ORM\Column(type="string", length=255, unique=true)
     * @Assert\NotBlank(message="user.fields.phoneCompany.constraints.notBlank")
     * @Groups({"users_read"})
     * @Assert\Type(
     *     type="string",
     *     message="typeError.string"
     * )
     */
    private $phone;

    /**
     * @var string
     * @ORM\Column(type="string", nullable=true)
     * @Assert\Type(
     *     type="string",
     *     message="typeError.string"
     * )
     */
    private $authCode;

    /**
     * @var boolean
     * @ORM\Column(type="boolean", nullable=true)
     * @Assert\Type(
     *     type="bool",
     *     message="typeError.bool"
     * )
     */
    private $isEnabled;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     * @Groups({"users_read"})
     * @Assert\Type(
     *     type="bool",
     *     message="typeError.bool"
     * )
     */
    private $isOnboarding;


    /**
     * @var DateTimeImmutable
     * @ORM\Column(type="datetime_immutable", nullable=true)
     * @Assert\Type(
     *     type="\DateTime",
     *     message="typeError.dateTime"
     * )
     */
    private $retrieveAt;

    /**
     * @var DateTimeImmutable
     * @ORM\Column(type="datetime_immutable", nullable=true)
     * @Assert\Type(
     *     type="\DateTimeInterface",
     *     message="typeError.dateTime"
     * )
     */
    private $lastLoginAt;

    /**
     * @ORM\ManyToOne(targetEntity=Role::class, inversedBy="users")
     * @Assert\Type(
     *     type="object",
     *     message="typeError.object"
     * )
     */
    private $userRole;

    /**
     * @ORM\ManyToOne(targetEntity=Language::class, inversedBy="users")
     * @ORM\JoinColumn(nullable=false)
     * @Assert\NotBlank(message="user.fields.language.constraints.notBlank")
     * @Groups({"users_read"})
     * @Assert\Type(
     *     type="object",
     *     message="typeError.object"
     * )
     */
    private $language;

    /**
     * @var string
     * @Gedmo\Slug(fields={"firstName", "lastName"})
     * @ORM\Column(type="string", length=255, unique=true)
     * @Assert\Type(
     *     type="string",
     *     message="typeError.string"
     * )
     */
    private $slug;

    /**
     * @ORM\OneToMany(targetEntity=Building::class, mappedBy="guardian")
     * @Assert\Type(
     *     type="object",
     *     message="typeError.object"
     * )
     */
    private $buildings;

    /**
     * @ORM\OneToMany(targetEntity=Logs::class, mappedBy="user")
     */
    private $logs;

    public function __construct()
    {
        $this->buildings = new ArrayCollection();
        $this->logs = new ArrayCollection();
    }

    use TimeStampableTrait;

    /**
     * @Groups({"users_read"})
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

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(?string $email): self
    {
        $this->email = $email;

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

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    /**
     * @deprecated since Symfony 5.3, use getUserIdentifier instead
     */
    public function getUsername(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->userRole ? [$this->userRole->getShortname()] : array();

        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }


    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Returning a salt is only needed, if you are not using a modern
     * hashing algorithm (e.g. bcrypt or sodium) in your security.yaml.
     *
     * @see UserInterface
     */
    public function getSalt(): ?string
    {
        return null;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function getIsEnabled(): ?bool
    {
        return $this->isEnabled;
    }

    public function setIsEnabled(bool $isEnabled): self
    {
        $this->isEnabled = $isEnabled;

        return $this;
    }

    public function getRetrieveAt(): ?\DateTimeImmutable
    {
        return $this->retrieveAt;
    }

    public function setRetrieveAt(?\DateTimeImmutable $retrieveAt): self
    {
        $this->retrieveAt = $retrieveAt;

        return $this;
    }

    public function getPersonalCode(): ?string
    {
        return $this->personalCode;
    }

    public function setPersonalCode(?string $personalCode): self
    {
        $this->personalCode = $personalCode;

        return $this;
    }

    public function getLastLoginAt(): ?\DateTimeImmutable
    {
        return $this->lastLoginAt;
    }

    public function setLastLoginAt(?\DateTimeImmutable $lastLoginAt): self
    {
        $this->lastLoginAt = $lastLoginAt;

        return $this;
    }

    public function getUserRole(): ?Role
    {
        return $this->userRole;
    }

    public function setUserRole(?Role $userRole): self
    {
        $this->userRole = $userRole;

        return $this;
    }

    public function getLanguage(): ?Language
    {
        return $this->language;
    }

    public function setLanguage(?Language $language): self
    {
        $this->language = $language;

        return $this;
    }

    public function isEmailAuthEnabled(): bool
    {
        return true; // This can be a persisted field to switch email code authentication on/off
    }

    public function getEmailAuthRecipient(): string
    {
        return $this->email;
    }

    public function getEmailAuthCode(): string
    {
        if (null === $this->authCode) {
            throw new \LogicException('The email authentication code was not set');
        }

        return $this->authCode;
    }

    public function setEmailAuthCode(string $authCode): void
    {
        $this->authCode = $authCode;
    }

    /**
     * @return Collection|Building[]
     */
    public function getBuildings(): Collection
    {
        return $this->buildings;
    }

    public function addBuilding(Building $building): self
    {
        if (!$this->buildings->contains($building)) {
            $this->buildings[] = $building;
            $building->setGuardian($this);
        }

        return $this;
    }

    public function removeBuilding(Building $building): self
    {
        if ($this->buildings->removeElement($building)) {
            // set the owning side to null (unless already changed)
            if ($building->getGuardian() === $this) {
                $building->setGuardian(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Logs[]
     */
    public function getLogs(): Collection
    {
        return $this->logs;
    }

    public function addLog(Logs $log): self
    {
        if (!$this->logs->contains($log)) {
            $this->logs[] = $log;
            $log->setUser($this);
        }

        return $this;
    }

    public function removeLog(Logs $log): self
    {
        if ($this->logs->removeElement($log)) {
            // set the owning side to null (unless already changed)
            if ($log->getUser() === $this) {
                $log->setUser(null);
            }
        }

        return $this;
    }


}
