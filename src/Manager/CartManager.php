<?php

namespace App\Manager;

use App\Entity\Order;
use App\Factory\OrderFactory;
use App\Storage\CartSessionStorage;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Security;
use App\Entity\User;

class CartManager
{

    private CartSessionStorage $cartSessionStorage;
    private OrderFactory $cartFactory;
    private EntityManagerInterface $entityManager;
    private Security $security;

    public function __construct(
        CartSessionStorage $cartStorage,
        OrderFactory $orderFactory,
        EntityManagerInterface $entityManager,
        Security $security
    ) {
        $this->cartSessionStorage = $cartStorage;
        $this->cartFactory = $orderFactory;
        $this->entityManager = $entityManager;
        $this->security = $security;
    }

    public function getCurrentCart(): Order
    {



        //BDD storage by user
        /** @var User $user */
        $user = $this->security->getUser();
        if(!empty($user)){
            $cartId = $user->getCartID();
            $cart = $this->cartSessionStorage->getCartUser($cartId);
        }else{
            $cart = $this->cartSessionStorage->getCart();
        }

        if (!$cart) {
            $cart = $this->cartFactory->create();
        }

        $this->cartSessionStorage->setCart($cart);

        return $cart;
    }

    public function save(Order $cart): void
    {
        // Persist in database
        $this->entityManager->persist($cart);

        // Persist in session
        $this->cartSessionStorage->setCart($cart);

        /** @var User $user */
        $user = $this->security->getUser();
        /*if(!empty($user)){
            $userId = $user->getId();
        }*/
        $cart->setUser($user); //Associe le pannier à l'utilisateur connecté
        $this->entityManager->flush();
    }
}

