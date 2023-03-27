<?php

namespace App\EventSubscriber;

use App\Event\UserLoggedInViaSocialNetworkEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class UserLoggedInViaSocialNetworkSendNotificationSubscriber implements EventSubscriberInterface
{
    public function onUserLoggedInViaSocialNetworkEvent(UserLoggedInViaSocialNetworkEvent $event): void
    {
        // Send password to client
    }

    public static function getSubscribedEvents(): array
    {
        return [
            UserLoggedInViaSocialNetworkEvent::class => 'onUserLoggedInViaSocialNetworkEvent'
        ];
    }
}