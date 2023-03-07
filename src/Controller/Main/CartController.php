<?php

namespace App\Controller\Main;

use App\Repository\CartRepository;
use App\Repository\OrderRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CartController extends AbstractController
{
    private OrderRepository $orderRepository;

    public function __construct(OrderRepository $orderRepository)
    {
        $this->orderRepository = $orderRepository;
    }

    #[Route('/cart', name: 'main_cart_show')]
    public function show(Request $request, CartRepository $cartRepository): Response
    {
        $phpSessionId = $request->cookies->get('PHPSESSID');
        $cart = $cartRepository->findOneBy(['sessionId' => $phpSessionId]);

        return $this->render('main/cart/show.html.twig', [
            'cart' => $cart,
        ]);
    }

    #[Route('/cart/create', name: 'main_cart_create')]
    public function create(Request $request, CartRepository $cartRepository): Response
    {
        $phpSessionId = $request->cookies->get('PHPSESSID');
        $user = $this->getUser();

        $this->orderRepository->createOrderFromCartBySessionId($phpSessionId, $user);

        return $this->redirectToRoute('main_cart_show', [

        ]);
    }
}
