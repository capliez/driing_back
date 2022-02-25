<?php

namespace App\Controller\Api;

use App\Repository\ResidentRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ResidentSearchController extends AbstractController
{
    /**
     * @var ResidentRepository
     */
    private $residentRepository;

    public function __construct(ResidentRepository $residentRepository)
    {
        $this->residentRepository = $residentRepository;
    }

    public function __invoke(string $idBuilding, string $lastName, bool $isHandedOver)
    {
        return $this->residentRepository->searchResidentByBuilding($idBuilding, $lastName, $isHandedOver);
    }
}
