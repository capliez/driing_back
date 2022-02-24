<?php

namespace App\Entity;

use App\Entity\Traits\TimeStampableTrait;
use App\Repository\PackageRepository;
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
 *     },
 *     "getAllPackageHandOver" = {
 *          "method": "GET",
 *          "path"="/packages/handedover/{idBuilding}",
 *          "controller"="App\Controller\Api\PackageHandedOverController",
 *          "read"=false,
 *          "openapi_context"=
 *          {
 *              "summary"="Récupére les colis non remis",
 *              "description"="Récupére les colis en fonction de l'immeuble non remis"
 *          }
 *     },
 *     "getNbPackageHandOver" = {
 *          "method": "GET",
 *          "path"="/packages/count/handedover/{idBuilding}",
 *          "controller"="App\Controller\Api\PackageNbHandedOverController",
 *          "read"=false,
 *          "openapi_context"=
 *          {
 *              "summary"="Récupére le nombre de colis non remis",
 *              "description"="Récupére les colis en fonction de l'immeuble non remis"
 *          }
 *     }
 *     },
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
     * @var boolean
     * @ORM\Column(type="boolean", nullable=true)
     * @Groups({"packages_read"})
     * @Assert\Type(
     *     type="bool",
     *     message="typeError.bool"
     * )
     */
    private $isHandedOver;

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

    public function getIsHandedOver(): ?bool
    {
        return $this->isHandedOver;
    }

    public function setIsHandedOver(?bool $isHandedOver): self
    {
        $this->isHandedOver = $isHandedOver;

        return $this;
    }


}
