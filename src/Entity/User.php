<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use App\Entity\Traits\TimeStampableTrait;
use Symfony\Component\Validator\Constraints as Assert;
use DateTimeImmutable;
use ApiPlatform\Core\Annotation\ApiResource;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=UserRepository::class)
 * @ORM\Table(name="`user`")
 * @UniqueEntity(
 *     fields={"email"},
 *     message="user.unique"
 * )
 * @ApiResource(
 *     collectionOperations={"GET", "POST"},
 *     itemOperations={"GET", "PUT"},
 *     normalizationContext={
 *          "groups"={"users_read"}
 *     },
 *     denormalizationContext={
 *          "disable_type_enforcement"=true
 *     }
 * )
 */
class User implements UserInterface, PasswordAuthenticatedUserInterface
{

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups({"users_read", "guardians_read"})
     */
    private $id;

    /**
     * @var string
     * @ORM\Column(type="string", length=180, unique=true)
     * @Assert\NotBlank(message="user.fields.email.constraints.notblank")
     * @Assert\Email(message="user.fields.email.constraints.valid")
     * @Assert\Type(
     *     type="string",
     *     message="typeError.string"
     * )
     * @Groups({"users_read", "guardians_read"})
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
     * @ORM\Column(type="string", length=6, nullable=true)
     * @Assert\Type(
     *     type="string",
     *     message="typeError.string"
     * )
     */
    private $personalCode;

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
     *     type="\DateTime",
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
     * @Groups({"users_read", "guardians_read"})
     * @Assert\Type(
     *     type="object",
     *     message="typeError.object"
     * )
     */
    private $language;

    /**
     * @ORM\OneToOne(targetEntity=Guardian::class, mappedBy="user", cascade={"persist", "remove"})
     * @Groups({"users_read"})
     * @Assert\Type(
     *     type="object",
     *     message="typeError.object"
     * )
     */
    private $guardian;

    use TimeStampableTrait;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

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

    public function getGuardian(): ?Guardian
    {
        return $this->guardian;
    }

    public function setGuardian(Guardian $guardian): self
    {
        // set the owning side of the relation if necessary
        if ($guardian->getUser() !== $this) {
            $guardian->setUser($this);
        }

        $this->guardian = $guardian;

        return $this;
    }


}
