<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Delete;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Post;
use App\Repository\CartProductRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: CartProductRepository::class)]
#[ApiResource(
    operations: [
        new Get(normalizationContext: ['groups' => 'cart_product:item']),
        new GetCollection(normalizationContext: ['groups' => 'cart_product:list']),
        new Post(
            normalizationContext: ['groups' => 'cart_product:list:write'],
            security: "is_granted('ROLE_ADMIN')",
            securityMessage: 'Sorry, but you are not an admin.'
        ),
        new Delete(),
    ]
)]
class CartProduct
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['cart_product:item','cart_product:list', 'cart:item', 'cart:list'])]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'cartProducts')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['cart_product:item','cart_product:list'])]
    private ?Cart $cart = null;

    #[ORM\ManyToOne(inversedBy: 'cartProducts')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['cart_product:item','cart_product:list', 'cart:item', 'cart:list'])]
    private ?Product $product = null;

    #[ORM\Column]
    #[Groups(['cart_product:item','cart_product:list', 'cart:item', 'cart:list'])]
    private ?int $quantity = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCart(): ?Cart
    {
        return $this->cart;
    }

    public function setCart(?Cart $cart): self
    {
        $this->cart = $cart;

        return $this;
    }

    public function getProduct(): ?Product
    {
        return $this->product;
    }

    public function setProduct(?Product $product): self
    {
        $this->product = $product;

        return $this;
    }

    public function getQuantity(): ?int
    {
        return $this->quantity;
    }

    public function setQuantity(int $quantity): self
    {
        $this->quantity = $quantity;

        return $this;
    }
}
