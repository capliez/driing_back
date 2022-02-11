<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Entity\Traits\TimeStampableTrait;
use App\Repository\PackageDetailRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=PackageDetailRepository::class)
 * @ApiResource(
 *     collectionOperations={"GET", "POST"},
 *     itemOperations={"GET", "PUT"},
 *     normalizationContext={
 *          "groups"={"packageDetails_read"}
 *     },
 *     denormalizationContext={
 *          "disable_type_enforcement"=true
 *     }
 * )
 */
class PackageDetail
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups({"packageDetails_read", "packages_read"})
     */
    private $id;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     * @Groups({"packageDetails_read", "packages_read"})
     * @Assert\Type(
     *     type="bool",
     *     message="typeError.bool"
     * )
     */
    private $isBulky;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     * @Groups({"packageDetails_read", "packages_read"})
     * @Assert\Type(
     *     type="bool",
     *     message="typeError.bool"
     * )
     */
    private $isDamaged;

    /**
     * @ORM\ManyToOne(targetEntity=Package::class, inversedBy="packageDetail")
     * @ORM\JoinColumn(nullable=false)
     * @Assert\NotBlank(message="packageDetail.fields.package.constraints.notBlank")
     * @Assert\Type(
     *     type="object",
     *     message="typeError.object"
     * )
     */
    private $package;

    use TimeStampableTrait;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getIsBulky(): ?bool
    {
        return $this->isBulky;
    }

    public function setIsBulky(?bool $isBulky): self
    {
        $this->isBulky = $isBulky;

        return $this;
    }

    public function getIsDamaged(): ?bool
    {
        return $this->isDamaged;
    }

    public function setIsDamaged(?bool $isDamaged): self
    {
        $this->isDamaged = $isDamaged;

        return $this;
    }

    public function getPackage(): ?Package
    {
        return $this->package;
    }

    public function setPackage(?Package $package): self
    {
        $this->package = $package;

        return $this;
    }

}
