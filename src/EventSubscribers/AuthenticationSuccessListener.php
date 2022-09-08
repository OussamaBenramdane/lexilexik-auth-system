<?php

namespace App\EventSubscribers;
use App\Entity\User;
use Lexik\Bundle\JWTAuthenticationBundle\Event\AuthenticationSuccessEvent;

/**
 * Authentication listener to add data into authentication success response.
 */

class AuthenticationSuccessListener

{
    /**
     * Event listener called by Symfony on auth success.*
     * @param AuthenticationSuccessEvent $event
     */
    public function onAuthenticationSuccessResponse(AuthenticationSuccessEvent $event): void
    {
        $data = $event->getData();
        /** @var User $user */
        $user = $event->getUser();
        if (!$user instanceof User) {
            return;
        }
        $data['data'] = array('id' => $user->getId());
        $event->setData($data);
    }

}
