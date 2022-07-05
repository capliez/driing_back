<?php

namespace App\Controller\Admin;

use App\Entity\Building;
use App\Entity\Resident;
use App\Entity\Package;
use App\Entity\User;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Config\UserMenu;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;

class DashboardController extends AbstractDashboardController
{
    /**
     * @Route("/4dm1n", name="app_admin")
     */
    public function index(): Response
    {
        return parent::index();
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('Espace Administration')
            ->setFaviconPath('assets/img/Favicon.png');
    }

    public function configureCrud(): Crud
    {
        return Crud::new()
            // this defines the pagination size for all CRUD controllers
            // (each CRUD controller can override this value if needed)
            ->setPaginatorPageSize(20)
            ->setEntityPermission('ROLE_ADMIN')
            ;
    }


    public function configureUserMenu(UserInterface $user): UserMenu
    {
        return parent::configureUserMenu($user)
            ->setName($user->getFullName())
            ->addMenuItems([
                MenuItem::linkToRoute('general.myAccount', 'fa fa-id-card', 'auth_login'),
            ]);
    }

    public function configureMenuItems(): iterable
    {
        yield MenuItem::linkToDashboard('Dashboard', 'fa fa-home');
        yield MenuItem::linkToCrud('user.namePlural', 'fas fa-users', User::class);
        yield MenuItem::linkToCrud('building.namePlural', 'fas fa-building', Building::class);
        yield MenuItem::linkToCrud('resident.namePlural', 'fas fa-address-book', Resident::class);
        yield MenuItem::linkToCrud('package.namePlural', 'fas fa-box-open', Package::class);
    }
}
