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

    /**
     * @param PackageRepository $packageRepository
     */
    public function __construct(PackageRepository $packageRepository)
    {
        $this->packageRepository = $packageRepository;
    }

    public function __invoke(string $idBuilding)
    {

        $packages = $this->packageRepository->findAllHandOver($idBuilding);
        $newPackages = Array();

        if($packages){
            foreach ($packages as $item) {
                $date = date_format($item->getCreatedAt(),'Y-m-d');

                if(array_search($date, $newPackages) !== 0){
                    $newPackages[$date][] = $item;
                }
            }
            ksort($newPackages);
            return Array(array_reverse($newPackages));
        }


        return $packages;
    }
}
