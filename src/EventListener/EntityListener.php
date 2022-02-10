<?php

namespace App\EventListener;

use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\EventSubscriber\EventSubscriberInterface;
use Doctrine\ORM\Events;
use Doctrine\Persistence\Event\LifecycleEventArgs;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Translation\TranslatableMessage;

class EntityListener implements EventSubscriberInterface
{

    private $session;

    public function __construct(SessionInterface $session)
    {
        $this->session = $session;
    }

    // this method can only return the event names; you cannot define a
    // custom method name to execute when each event triggers
    public function getSubscribedEvents(): array
    {
        return [
            Events::postPersist,
            Events::postRemove,
            Events::postUpdate
        ];
    }


    // callback methods must be called exactly like the events they listen to;
    // they receive an argument of type LifecycleEventArgs, which gives you access
    // to both the entity object of the event and the entity manager itself
    public function postPersist(LifecycleEventArgs $args): void
    {
        if (!$args->getObject() instanceof User) {
            return;
        }

        $this->logActivity('persist', $args);

        $this->session->getFlashBag()->add('success', new TranslatableMessage('user.message.create', [
            '%name%' => (string) $args->getObject()->getFullname(),
        ], 'messages'));
    }

    public function postRemove(LifecycleEventArgs $args): void
    {
        if (!$args->getObject() instanceof User) {
            return;
        }

        $this->logActivity('remove', $args);


        $this->session->getFlashBag()->add('success', new TranslatableMessage('product.message.remove', [
            '%name%' => (string) $args->getObject()->getName(),
        ], 'messages'));
    }

    public function postUpdate(LifecycleEventArgs $args): void
    {
        if (!$args->getObject() instanceof User) {
            return;
        }

        $this->logActivity('update', $args);


        $this->session->getFlashBag()->add('success', new TranslatableMessage('product.message.update', [
            '%name%' => (string) $args->getObject()->getName(),
        ], 'messages'));
    }

    private function logActivity(string $action, LifecycleEventArgs $args): void
    {
        $entity = $args->getObject();

        // if this subscriber only applies to certain entity types,
        // add some code to check the entity type as early as possible
        if (!$entity instanceof User) {
            return;
        }

    }
}