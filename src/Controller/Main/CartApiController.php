<?php

namespace App\Controller\Main;

use App\Entity\Cart;
use App\Entity\CartProduct;
use App\Repository\CartProductRepository;
use App\Repository\CartRepository;
use App\Repository\ProductRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api', name: 'main_api_')]
class CartApiController extends AbstractController
{
    private ProductRepository $productRepository;
    private CartRepository $cartRepository;
    private CartProductRepository $cartProductRepository;
    private EntityManagerInterface $entityManager;

    public function __construct(
        ProductRepository $productRepository,
        CartRepository $cartRepository,
        CartProductRepository $cartProductRepository,
        EntityManagerInterface $entityManager
    )
    {
        $this->productRepository = $productRepository;
        $this->cartRepository = $cartRepository;
        $this->cartProductRepository = $cartProductRepository;
        $this->entityManager = $entityManager;
    }

    #[Route('/cart', name: 'cart_save', methods: 'POST')]
    public function cartSave(Request $request): Response
    {
        $productId = $request->request->get('productId');
        $phpSessionId = $request->cookies->get('PHPSESSID');
        $product = $this->productRepository->findOneBy(['uuid' => $productId]);

        $cart = $this->cartRepository->findOneBy(['sessionId' => $phpSessionId]);

        if (!$cart) {
            $cart = new Cart();
            $cart->setSessionId($phpSessionId);
        }

        $cartProduct = $this->cartProductRepository->findOneBy(['cart' => $cart, 'product' => $product]);

        if (!$cartProduct) {
            $cartProduct = new CartProduct();
            $cartProduct->setCart($cart);
            $cartProduct->setQuantity(1);
            $cartProduct->setProduct($product);

            $cart->addCartProduct($cartProduct);
        } else {
            $quantity = $cartProduct->getQuantity() + 1;
            $cartProduct->setQuantity($quantity);
        }

        $this->entityManager->persist($cart);
        $this->entityManager->persist($cartProduct);
        $this->entityManager->flush();



        return new JsonResponse([
            'success' => false,
            'data' => [
                'test' => 123
            ]
        ]);
    }
}
