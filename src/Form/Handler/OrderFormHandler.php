<?php

namespace App\Form\Handler;

use App\Entity\Order;
use App\Repository\OrderRepository;

class OrderFormHandler
{
    private OrderRepository $orderRepository;

    public function __construct(OrderRepository $orderRepository)
    {
        $this->orderRepository = $orderRepository;
    }

    public function processEditForm(Order $order): Order
    {
        $this->orderRepository->save($order, true);

        return $order;
    }
}