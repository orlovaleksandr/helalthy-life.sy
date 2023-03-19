<?php

namespace App\Utils\ApiPlatform\EventSubscriber;

use ApiPlatform\Symfony\EventListener\EventPriorities;
use App\Entity\Order;
use App\Enums\OrderStatus;
use App\Event\OrderCreatedFromCartEvent;
use App\Repository\OrderRepository;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Event\ViewEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Bundle\SecurityBundle\Security;


class MakeOrderFromCartSubscriber implements EventSubscriberInterface
{
    private Security $security;
    private OrderRepository $orderRepository;
    private EventDispatcherInterface $eventDispatcher;

    public function __construct(
        Security $security,
        OrderRepository $orderRepository,
        EventDispatcherInterface $eventDispatcher
    )
    {
        $this->security = $security;
        $this->orderRepository = $orderRepository;
        $this->eventDispatcher = $eventDispatcher;
    }

    public function makeOrder(ViewEvent $event): void
    {
        /** @var Order $order */
        $order = $event->getControllerResult();
        $method = $event->getRequest()->getMethod();
        $orderClass = Order::class;

        if (!$order instanceof $orderClass || Request::METHOD_POST !== $method) {
            return;
        }

        $user = $this->security->getUser();
        if (!$user) {
            return;
        }

        $order->setOwner($user);

        $contentJson = $event->getRequest()->getContent();
        if (!$contentJson) {
            return;
        }

        $content = json_decode($contentJson, true);
        if (!array_key_exists('cartId', $content)) {
            return;
        }

        $cartId = (int)$content['cartId'];
        $this->orderRepository->addOrderProductsFromCart($order, $cartId);
        $this->orderRepository->recalculateOrderTotalPrice($order);

        $order->setStatus(OrderStatus::CREATED->value);
    }

    public function sendNotificationsAboutNewOrder(ViewEvent $event): void
    {
        /** @var Order $order */
        $order = $event->getControllerResult();
        $method = $event->getRequest()->getMethod();
        $orderClass = Order::class;

        if (!$order instanceof $orderClass || Request::METHOD_POST !== $method) {
            return;
        }

        $this->eventDispatcher->dispatch(new OrderCreatedFromCartEvent($order));
    }

    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::VIEW => [
                [
                    'makeOrder', EventPriorities::PRE_WRITE
                ],
                [
                    'sendNotificationsAboutNewOrder', EventPriorities::POST_WRITE
                ]
            ],
        ];
    }
}