<?php

namespace App\Controller\Api;

use App\Repository\ResidentRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ResidentController extends AbstractController
{
    /**
     * @var ResidentRepository
     */
    private $residentRepository;

    public function __construct(ResidentRepository $residentRepository)
    {
        $this->residentRepository = $residentRepository;
    }

    public function __invoke(string $id)
    {
        return $this->residentRepository->findAllByBuilding($id);
    }
}
