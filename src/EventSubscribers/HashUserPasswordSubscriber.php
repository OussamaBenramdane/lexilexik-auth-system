<?php

namespace App\EventSubscribers;

use App\Entity\User;
use Doctrine\ORM\Events;
use Doctrine\Persistence\Event\LifecycleEventArgs;
use Doctrine\Bundle\DoctrineBundle\EventSubscriber\EventSubscriberInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class HashUserPasswordSubscriber implements EventSubscriberInterface
{

    public function __construct(private readonly UserPasswordHasherInterface $hasher)
    {
    }

    public function getSubscribedEvents(): array
    {
        return [
            Events::prePersist,
            Events::preUpdate,
        ];
    }

    public function prePersist(LifecycleEventArgs $args ): void
    {
        $this->HashUserPassword($args);
    }

    public function preUpdate(LifecycleEventArgs $args): void
    {
        $this->HashUserPassword($args);
    }

    public function HashUserPassword (LifecycleEventArgs $args) :void
    {
        $user = $args->getObject();
        // if this subscriber only applies to certain entity types,
        // add some code to check the entity type as early as possible
        if (!$user instanceof User) {
            return;
        }
        $plainPassword = $user->getPassword();
        $hashedPassword = $this->hasher->hashPassword($user,$plainPassword);
        if($hashedPassword != $user->getPassword()){
            $user->setPassword($hashedPassword);
        }
    }
}