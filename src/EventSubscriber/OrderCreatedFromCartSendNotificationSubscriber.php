<?php

namespace App\EventSubscriber;

use App\Event\OrderCreatedFromCartEvent;
use App\Utils\Mailer\Sender\OrderCreatedFromCartEmailSender;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class OrderCreatedFromCartSendNotificationSubscriber implements EventSubscriberInterface
{

    private OrderCreatedFromCartEmailSender $cartEmailSender;

    public function __construct(OrderCreatedFromCartEmailSender $cartEmailSender)
    {
        $this->cartEmailSender = $cartEmailSender;
    }

    public function onOrderCreatedFromCartEvent(OrderCreatedFromCartEvent $event): void
    {
        $order = $event->getOrder();

        $this->cartEmailSender->sendEmailToClient($order);
        $this->cartEmailSender->sendEmailToManager($order);
    }

    public static function getSubscribedEvents(): array
    {
        return [
            OrderCreatedFromCartEvent::class => 'onOrderCreatedFromCartEvent',
        ];
    }
}
