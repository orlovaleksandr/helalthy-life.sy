<?php

namespace App\Repository;

use App\Entity\Cart;
use App\Entity\Order;
use App\Entity\OrderProduct;
use App\Entity\Product;
use App\Entity\User;
use App\Enums\OrderStatus;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Order>
 *
 * @method Order|null find($id, $lockMode = null, $lockVersion = null)
 * @method Order|null findOneBy(array $criteria, array $orderBy = null)
 * @method Order[]    findAll()
 * @method Order[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class OrderRepository extends ServiceEntityRepository
{
    private CartRepository $cartRepository;

    public function __construct(ManagerRegistry $registry, CartRepository $cartRepository)
    {
        parent::__construct($registry, Order::class);
        $this->cartRepository = $cartRepository;
    }

    public function save(Order $entity, bool $flush = false): void
    {
        $entity->setUpdatedAt(new \DateTimeImmutable());
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Order $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function markAsDeleted(Order $order): void
    {
        $order->setIsDeleted(true);
        $this->save($order, true);
    }

    public function addOrderProductsFromCart(Order $order, int $cartId)
    {
        $cart = $this->cartRepository->findOneBy(['id' => $cartId]);

        if ($cart) {
            foreach ($cart->getCartProducts()->getValues() as $cartProduct) {
                $orderProduct = new OrderProduct();
                $orderProduct->setAppOrder($order);
                $orderProduct->setQuantity($cartProduct->getQuantity());
                $orderProduct->setPricePerOne($cartProduct->getProduct()->getPrice());
                $orderProduct->setProduct($cartProduct->getProduct());

                $order->addOrderProduct($orderProduct);
                $this->getEntityManager()->persist($orderProduct);
            }
        }
    }

    public function createOrderFromCart(Cart $cart, User $user): void
    {
        $order = new Order();
        $order->setOwner($user);
        $order->setStatus(OrderStatus::CREATED->value);

        $this->addOrderProductsFromCart($order, $cart->getId());
        $this->recalculateOrderTotalPrice($order);

        $this->getEntityManager()->persist($order);
        $this->getEntityManager()->flush();

        $this->cartRepository->remove($cart, true);

    }

    public function recalculateOrderTotalPrice(Order $order): void
    {
        $orderTotalPrice = 0;

        foreach ($order->getOrderProducts()->getValues() as $orderProduct) {
            $orderTotalPrice += $orderProduct->getQuantity() * $orderProduct->getPricePerOne();
        }

        $order->setTotalPrice($orderTotalPrice);
    }

    public function createOrderFromCartBySessionId(string $phpSessionId, User $user): void
    {
        $cart = $this->cartRepository->findOneBy(['sessionId' => $phpSessionId]);
        if ($cart) {
            $this->createOrderFromCart($cart, $user);
        }
    }

//    /**
//     * @return Order[] Returns an array of Order objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('o')
//            ->andWhere('o.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('o.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Order
//    {
//        return $this->createQueryBuilder('o')
//            ->andWhere('o.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
