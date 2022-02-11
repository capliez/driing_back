<?php

namespace App\EventListener;

use App\Entity\Logs;
use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\Bundle\DoctrineBundle\EventSubscriber\EventSubscriberInterface;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Events;
use Doctrine\Persistence\Event\LifecycleEventArgs;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Translation\TranslatableMessage;

class EntityListener implements EventSubscriberInterface
{
    /**
     * @var UserRepository
     */
    private $userRepository;

    /**
     * @var SessionInterface
     */
    private $session;

    /**
     * @var Security
     */
    private $security;


    /**
     * @param UserRepository $userRepository
     * @param SessionInterface $session
     * @param Security $security
     */
    public function __construct(UserRepository $userRepository, SessionInterface $session, Security $security)
    {
        $this->userRepository = $userRepository;
        $this->session = $session;
        $this->security = $security;
    }

    // this method can only return the event names; you cannot define a
    // custom method name to execute when each event triggers
    public function getSubscribedEvents(): array
    {
        return [
            Events::preUpdate
        ];
    }

    public function preUpdate(LifecycleEventArgs $args): void
    {
        if (!$args->getObject() instanceof User) {
            return;
        }

        $this->logActivity('update', $args);
    }

    private function logActivity(string $action, LifecycleEventArgs $args): void
    {
        $user = $args->getObject();
        // if this subscriber only applies to certain entity types,
        // add some code to check the entity type as early as possible
        if (!$user instanceof User) {
            return;
        }


        $this->session->getFlashBag()->add('success', new TranslatableMessage('user.message.' . $action, [
            '%name%' => (string)$args->getObject()->getFullname(),
        ], 'messages'));
    }

}