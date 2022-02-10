<?php

namespace App\Security;

use App\Entity\User as AppUser;
use App\Service\SecurizerRole;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Security\Core\Exception\CustomUserMessageAccountStatusException;
use Symfony\Component\Security\Core\User\UserCheckerInterface;
use Symfony\Component\Security\Core\User\UserInterface;

class UserChecker implements UserCheckerInterface
{

    /**
     * @var EntityManagerInterface
     */
    private $manager;

    /**
     * @var SecurizerRole
     */
    private $securizerRole;

    /**
     * @var RequestStack
     */
    private $requestStack;

    public function __construct(EntityManagerInterface $manager, SecurizerRole $securizerRole, RequestStack $requestStack)
    {
        $this->manager = $manager;
        $this->securizerRole = $securizerRole;
        $this->requestStack = $requestStack;
    }

    public function checkPreAuth(UserInterface $user): void
    {

        $routeFirewall = $this->requestStack->getCurrentRequest()->attributes->get('_route');

        if (!$user instanceof AppUser) {
            return;
        }

        if (!$user->getIsEnabled()) {
            // the message passed to this exception is meant to be displayed to the user
            throw new CustomUserMessageAccountStatusException('Your user account is not enabled.');
        } else if ($routeFirewall == "auth_login" && !$this->securizerRole->isGranted($user, "ROLE_ADMIN")) {
            // the message passed to this exception is meant to be displayed to the user
            throw new CustomUserMessageAccountStatusException('Your user account does not have rights.');
        }
    }

    public function checkPostAuth(UserInterface $user): void
    {
        if (!$user instanceof AppUser) {
            return;
        }

        try {
            $user->setLastLoginAt(new \DateTimeImmutable());
            $this->manager->flush();
        } catch (\Exception $e) {
            dd($e);
        }
    }
}