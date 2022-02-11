<?php

namespace App\Entity;

use App\Entity\Traits\TimeStampableTrait;
use App\Repository\PackageRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use ApiPlatform\Core\Annotation\ApiResource;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=PackageRepository::class)
 * @ApiResource(
 *     collectionOperations={"GET", "POST"},
 *     itemOperations={"GET", "PUT",
 *     "getAll" = {
 *          "method": "GET",
 *          "path"="/packages/building/{id}",
 *          "controller"="App\Controller\Api\PackageController",
 *          "read"=false,
 *          "openapi_context"=
 *          {
 *              "summary"="Récupére les colis",
 *              "description"="Récupére les colis en fonction de l'immeuble"
 *          }
 *     }},
 *     normalizationContext={
 *          "groups"={"packages_read"}
 *     },
 *     denormalizationContext={
 *          "disable_type_enforcement"=true
 *     }
 * )
 */
class Package
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups({"packages_read"})
     */
    private $id;

    /**
     * @ORM\Column(type="integer")
     * @Assert\NotBlank(message="package.fields.nbPackage.constraints.notBlank")
     * @Groups({"packages_read"})
     * @Assert\Type(
     *     type="integer",
     *     message="typeError.integer"
     * )
     */
    private $nbPackage;

    /**
     * @ORM\ManyToOne(targetEntity=Building::class, inversedBy="packages")
     * @ORM\JoinColumn(nullable=false)
     * @Assert\NotBlank(message="package.fields.building.constraints.notBlank")
     * @Assert\Type(
     *     type="object",
     *     message="typeError.object"
     * )
     */
    private $building;

    /**
     * @ORM\ManyToOne(targetEntity=Resident::class, inversedBy="packages")
     * @ORM\JoinColumn(nullable=false)
     * @Assert\NotBlank(message="package.fields.resident.constraints.notBlank")
     * @Groups({"packages_read"})
     * @Assert\Type(
     *     type="object",
     *     message="typeError.object"
     * )
     */
    private $resident;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="packages")
     * @ORM\JoinColumn(nullable=false)
     * @Assert\NotBlank(message="package.fields.guardian.constraints.notBlank")
     * @Assert\Type(
     *     type="object",
     *     message="typeError.object"
     * )
     */
    private $guardian;

    /**
     * @ORM\OneToMany(targetEntity=PackageDetail::class, mappedBy="package")
     * @Groups({"packages_read"})
     * @Assert\Type(
     *     type="object",
     *     message="typeError.object"
     * )
     */
    private $packageDetail;

    public function __construct()
    {
        $this->packageDetail = new ArrayCollection();
    }


    use TimeStampableTrait;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNbPackage(): ?int
    {
        return $this->nbPackage;
    }

    public function setNbPackage(int $nbPackage): self
    {
        $this->nbPackage = $nbPackage;

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

    public function getResident(): ?Resident
    {
        return $this->resident;
    }

    public function setResident(?Resident $resident): self
    {
        $this->resident = $resident;

        return $this;
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

    /**
     * @return Collection|PackageDetail[]
     */
    public function getPackageDetail(): Collection
    {
        return $this->packageDetail;
    }

    public function addPackageDetail(PackageDetail $packageDetail): self
    {
        if (!$this->packageDetail->contains($packageDetail)) {
            $this->packageDetail[] = $packageDetail;
            $packageDetail->setPackage($this);
        }

        return $this;
    }

    public function removePackageDetail(PackageDetail $packageDetail): self
    {
        if ($this->packageDetail->removeElement($packageDetail)) {
            // set the owning side to null (unless already changed)
            if ($packageDetail->getPackage() === $this) {
                $packageDetail->setPackage(null);
            }
        }

        return $this;
    }


}
