<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Entity\Traits\TimeStampableTrait;
use App\Repository\ResidentRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;


/**
 * @ORM\Entity(repositoryClass=ResidentRepository::class)
 * @UniqueEntity(
 *     fields={"phone"},
 *     message="resident.unique"
 * )
 * @ApiResource(
 *     collectionOperations={"POST", "GET"},
 *     itemOperations={"GET", "PUT",
 *     "getAll" = {
 *          "method": "GET",
 *          "path"="/residents/building/{id}",
 *          "controller"="App\Controller\Api\ResidentController",
 *          "read"=false,
 *          "openapi_context"=
 *          {
 *              "summary"="Récupére les résidents",
 *              "description"="Récupére les résidents en fonction de l'immeuble"
 *          }
 *     },
 *     "searchResident" = {
 *          "method": "GET",
 *          "path"="/residents/search/{idBuilding}/{lastName}/{isHandedOver}",
 *          "controller"="App\Controller\Api\ResidentSearchController",
 *          "read"=false,
 *          "openapi_context"=
 *          {
 *              "summary"="Rechercher un résident",
 *              "description"="Recherche un résident dans un immeuble"
 *          }
 *     }
 *     },
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
     * @Groups({"residents_read", "packages_read"})
     */
    private $id;

    /**
     * @var string
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message="resident.fields.lastName.constraints.notBlank")
     * @Assert\Regex(pattern="/\d/", match=false, message="resident.fields.lastName.constraints.regex")
     * @Groups({"residents_read", "packages_read"})
     * @Assert\Type(
     *     type="string",
     *     message="typeError.string"
     * )
     */
    private $lastName;

    /**
     * @var string
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message="resident.fields.phone.constraints.notBlank")
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

    /**
     * @ORM\OneToMany(targetEntity=Package::class, mappedBy="resident")
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
        $this->packages = new ArrayCollection();
    }

    /**
     * @Groups({"residents_read"})
     * @return int
     */
    public function getCountPackages(): int
    {
        return count($this->packages);
    }

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
            $package->setResident($this);
        }

        return $this;
    }

    public function removePackage(Package $package): self
    {
        if ($this->packages->removeElement($package)) {
            // set the owning side to null (unless already changed)
            if ($package->getResident() === $this) {
                $package->setResident(null);
            }
        }

        return $this;
    }

    public function getIsEnabled(): ?bool
    {
        return $this->isEnabled;
    }

    public function setIsEnabled(?bool $isEnabled): self
    {
        $this->isEnabled = $isEnabled;

        return $this;
    }

    /**
     * @Groups({"residents_read"})
     * @return bool
     */
    public function isPackageHandedOver(): ?bool
    {
        $isResult = false;

        foreach ($this->packages as $package) {
            if (!$package->getIsHandedOver()) $isResult = true;
        }

        return $isResult;
    }
}
