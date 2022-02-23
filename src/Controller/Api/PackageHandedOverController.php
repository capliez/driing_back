<?php

namespace App\Controller\Api;

use App\Repository\PackageRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class PackageHandedOverController extends AbstractController
{
    /**
     * @var PackageRepository
     */
    private $packageRepository;

    public function __construct(PackageRepository $packageRepository)
    {
        $this->packageRepository = $packageRepository;
    }

    public function __invoke(string $idBuilding)
    {
        return $this->packageRepository->findAllHandOver($idBuilding);
    }
}
