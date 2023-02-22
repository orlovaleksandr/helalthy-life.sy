<?php

namespace App\Controller;

use App\Entity\Product;
use App\Form\EditProductFormType;
use App\Repository\ProductRepository;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DefaultController extends AbstractController
{
    private ProductRepository $productRepository;
    public function __construct(ProductRepository $productRepository)
    {
        $this->productRepository = $productRepository;
    }

    #[Route(path: '/', name: 'homePage')]
    public function index(): Response
    {
        $products = $this->productRepository->findAll();

        return $this->render('main/default/index.html.twig', [
            'products' => $products,
        ]);
    }

    #[Route(path: '/product-add', name: 'productAdd')]
    public function productAdd(): Response
    {
        $product = new Product();
        $product->setTitle('Product' . random_int(1, 100));
        $product->setDescription('Test');
        $product->setPrice(10);
        $product->setQuantity(1);

        $this->productRepository->save($product, true);

        return $this->redirectToRoute('homePage');
    }

    #[Route(path: '/edit-product/{id}', name: 'editProduct', requirements: ['id' => '\d+'], methods: ['GET', 'POST'],)]
    #[Route(path: '/add-product', name: 'addProduct', methods: ['GET', 'POST'],)]
    public function editProduct(Request $request, int $id = null): Response
    {
        if ($id) {
            $product = $this->productRepository->find($id);
        } else {
            $product = new Product();
        }

        $form = $this->createForm(EditProductFormType::class, $product);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->productRepository->save($form->getData(), true);

            return $this->redirectToRoute('editProduct', ['id' => $product->getId()]);
        }

        return $this->render('main/default/edit_product.html.twig', ['form' => $form->createView()]);
    }
}
