<?php

namespace App\Entity;

use App\Entity\Traits\TimeStampableTrait;
use App\Repository\BuildingRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;
use Gedmo\Mapping\Annotation as Gedmo;
use ApiPlatform\Core\Annotation\ApiResource;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=BuildingRepository::class)
 * @UniqueEntity(
 *     fields={"name"},
 *     message="building.unique"
 * )
 * @ApiResource(
 *     collectionOperations={"GET"},
 *     itemOperations={"GET"},
 *     normalizationContext={
 *          "groups"={"buildings_read"}
 *     },
 *     denormalizationContext={
 *          "disable_type_enforcement"=true
 *     }
 * )
 */
class Building
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups({"buildings_read"})
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="buildings")
     * @ORM\JoinColumn(nullable=false)
     * @Assert\Type(
     *     type="object",
     *     message="typeError.object"
     * )
     */
    private $guardian;

    /**
     * @var string
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message="building.fields.name.constraints.notBlank")
     * @Assert\Type(
     *     type="string",
     *     message="typeError.string"
     * )
     * @Groups({"buildings_read"})
     */
    private $name;

    /**
     * @var string
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message="building.fields.address.constraints.notBlank")
     * @Assert\Type(
     *     type="string",
     *     message="typeError.string"
     * )
     * @Groups({"buildings_read"})
     */
    private $address;

    /**
     * @var string
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message="building.fields.postcode.constraints.notBlank")
     * @Assert\Type(
     *     type="string",
     *     message="typeError.string"
     * )
     * @Groups({"buildings_read"})
     */
    private $postcode;

    /**
     * @var string
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message="building.fields.city.constraints.notBlank")
     * @Assert\Type(
     *     type="string",
     *     message="typeError.string"
     * )
     * @Groups({"buildings_read"})
     */
    private $city;

    /**
     * @var string
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message="building.fields.country.constraints.notBlank")
     * @Assert\Type(
     *     type="string",
     *     message="typeError.string"
     * )
     * @Groups({"buildings_read"})
     */
    private $country;

    /**
     * @ORM\OneToMany(targetEntity=Resident::class, mappedBy="building")
     * @Assert\Type(
     *     type="object",
     *     message="typeError.object"
     * )
     */
    private $residents;

    /**
     * @var string
     * @Gedmo\Slug(fields={"name"})
     * @ORM\Column(type="string", length=255, unique=true)
     * @Assert\Type(
     *     type="string",
     *     message="typeError.string"
     * )
     */
    private $slug;

    /**
     * @ORM\OneToMany(targetEntity=Package::class, mappedBy="building")
     */
    private $packages;

    /**
     * @var boolean
     * @ORM\Column(type="boolean", nullable=true)
     * @Assert\Type(
     *     type="bool",
     *     message="typeError.bool"
     * )
     */
    private $isEnabled;

    use TimeStampableTrait;

    public function __construct()
    {
        $this->residents = new ArrayCollection();
        $this->packages = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getGuardian(): ?User
    {
        return $this->guardian;
    }

    public function setGuardian(?User $guardian): self
    {
        $this->guardian = $guardian;

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

    public function getAddress(): ?string
    {
        return $this->address;
    }

    public function setAddress(string $address): self
    {
        $this->address = $address;

        return $this;
    }

    public function getPostcode(): ?string
    {
        return $this->postcode;
    }

    public function setPostcode(string $postcode): self
    {
        $this->postcode = $postcode;

        return $this;
    }

    public function getCity(): ?string
    {
        return $this->city;
    }

    public function setCity(string $city): self
    {
        $this->city = $city;

        return $this;
    }

    public function getCountry(): ?string
    {
        return $this->country;
    }

    public function setCountry(string $country): self
    {
        $this->country = $country;

        return $this;
    }

    /**
     * @return Collection|Resident[]
     */
    public function getResidents(): Collection
    {
        return $this->residents;
    }

    public function addResident(Resident $resident): self
    {
        if (!$this->residents->contains($resident)) {
            $this->residents[] = $resident;
            $resident->setBuilding($this);
        }

        return $this;
    }

    public function removeResident(Resident $resident): self
    {
        if ($this->residents->removeElement($resident)) {
            // set the owning side to null (unless already changed)
            if ($resident->getBuilding() === $this) {
                $resident->setBuilding(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Package[]
     */
    public function getPackages(): Collection
    {
        return $this->packages;
    }

    public function addPackage(Package $package): self
    {
        if (!$this->packages->contains($package)) {
            $this->packages[] = $package;
            $package->setBuilding($this);
        }

        return $this;
    }

    public function removePackage(Package $package): self
    {
        if ($this->packages->removeElement($package)) {
            // set the owning side to null (unless already changed)
            if ($package->getBuilding() === $this) {
                $package->setBuilding(null);
            }
        }

        return $this;
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

    public function __toString()
    {
        return $this->name;
    }
}
