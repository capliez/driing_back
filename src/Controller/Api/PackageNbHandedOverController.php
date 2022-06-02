<?php

namespace App\Controller\Api;

use App\Repository\PackageRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class PackageNbHandedOverController extends AbstractController
{
    /**
     * @var PackageRepository
     */
    private $packageRepository;

    /**
     * ss
     * @param PackageRepository $packageRepository
     */
    public function __construct(PackageRepository $packageRepository)
    {
        $this->packageRepository = $packageRepository;
    }

    public function __invoke(string $idBuilding)
    {

        $nbPackages = $this->packageRepository->countPackageNoHandedOver($idBuilding);

        return $nbPackages[0];
    }
}
