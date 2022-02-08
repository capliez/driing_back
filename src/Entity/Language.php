<?php

namespace App\Entity;

use App\Repository\LanguageRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;
use App\Entity\Traits\TimeStampableTrait;
use ApiPlatform\Core\Annotation\ApiResource;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=LanguageRepository::class)
 * @UniqueEntity("shortname", message="language.unique")
 * @ApiResource(
 *     collectionOperations={"GET", "POST"},
 *     itemOperations={"GET", "PUT"},
 *     normalizationContext={
 *          "groups"={"languages_read"}
 *     },
 *     denormalizationContext={
 *          "disable_type_enforcement"=true
 *     }
 * )
 */
class Language
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups({"languages_read"})
     */
    private $id;

    /**
     * @var string
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message="language.fields.name.constraints.notblank")
     * @Groups({"languages_read"})
     * @Assert\Type(
     *     type="string",
     *     message="typeError.string"
     * )
     */
    private $name;

    /**
     * @var string
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message="language.fields.shortname.constraints.notblank")
     * @Groups({"languages_read", "users_read", "guardians_read"})
     * @Assert\Type(
     *     type="string",
     *     message="typeError.string"
     * )
     */
    private $shortname;

    /**
     * @ORM\OneToMany(targetEntity=User::class, mappedBy="language")
     * @Assert\Type(
     *     type="object",
     *     message="typeError.object"
     * )
     */
    private $users;

    public function __construct()
    {
        $this->users = new ArrayCollection();
    }

    use TimeStampableTrait;

    public function getId(): ?int
    {
        return $this->id;
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

    public function getShortname(): ?string
    {
        return $this->shortname;
    }

    public function setShortname(string $shortname): self
    {
        $this->shortname = $shortname;

        return $this;
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
            $user->setLanguage($this);
        }

        return $this;
    }

    public function removeUser(User $user): self
    {
        if ($this->users->removeElement($user)) {
            // set the owning side to null (unless already changed)
            if ($user->getLanguage() === $this) {
                $user->setLanguage(null);
            }
        }

        return $this;
    }
}
