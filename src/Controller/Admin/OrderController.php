<?php

namespace App\Controller\Admin;

use App\Entity\Order;
use App\Enums\OrderStatus;
use App\Form\Admin\EditOrderFormType;
use App\Form\Handler\OrderFormHandler;
use App\Repository\OrderRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin/order', name: 'admin_order_')]
class OrderController extends AbstractController
{
    private OrderRepository $orderRepository;

    public function __construct(OrderRepository $orderRepository)
    {
        $this->orderRepository = $orderRepository;
    }

    #[Route('/list', name: 'list')]
    public function list(): Response
    {
        $orders = $this->orderRepository->findBy(['isDeleted' => false], ['id' => 'DESC']);

        return $this->render('admin/order/list.html.twig', [
            'orders' => $orders,
            'ordersStatuses' => OrderStatus::getStringCases()
        ]);
    }

    #[Route('/edit/{id}', name: 'edit')]
    #[Route('/add', name: 'add')]
    public function edit(Request $request, OrderFormHandler $orderFormHandler, Order $order = null): Response
    {
        if (!$order) {
            $order = new Order();
        }

        $form = $this->createForm(EditOrderFormType::class, $order);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $order = $orderFormHandler->processEditForm($order);
            $this->addFlash(type: 'success', message: 'Your changes ware saved!');

            return $this->redirectToRoute('admin_order_edit', ['id' => $order->getId()]);
        }

        if ($form->isSubmitted() && !$form->isValid()) {
            $this->addFlash(type: 'warning', message: 'Something went wrong! Please check you form!');
        }

        $orderProducts = [];

//        foreach ($order->getOrderProducts()->getValues() as $orderProduct) {
//            $orderProducts[] = [
//                'id' => $orderProduct->getId(),
//                'product' => [
//                    'id' => $orderProduct->getProduct()->getId(),
//                    'title' => $orderProduct->getProduct()->getTitle(),
//                    'price' => $orderProduct->getProduct()->getPrice(),
//                    'quantity' => $orderProduct->getProduct()->getQuantity(),
//                    'category' => [
//                        'id' => $orderProduct->getProduct()->getCategory()->getId(),
//                        'title' => $orderProduct->getProduct()->getCategory()->getTitle()
//                    ]
//                ],
//                'quantity' => $orderProduct->getQuantity(),
//                'pricePerOne' => $orderProduct->getPricePerOne(),
//
//            ];
//        }

        return $this->render('admin/order/edit.html.twig', [
            'order' => $order,
            'orderProducts' => $orderProducts,
            'form' => $form->createView()
        ]);
    }

    #[Route('/delete/{id}', name: 'delete')]
    public function delete(Order $order): Response
    {
        $this->orderRepository->markAsDeleted($order);

        $this->addFlash(type: 'warning', message: 'The order was successful deleted!');

        return $this->redirectToRoute('admin_order_list');
    }
}
