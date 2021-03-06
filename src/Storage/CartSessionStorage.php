<?php

namespace App\Storage;

use App\Entity\Order;
use App\Entity\User;
use App\Repository\OrderRepository;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Security\Core\Security;

class CartSessionStorage
{
    private RequestStack $requestStack;
    private OrderRepository $cartRepository;
    private Security $security;
    const CART_KEY_NAME = 'cart_id';

    public function __construct(RequestStack $requestStack, OrderRepository $cartRepository, Security $security)
    {
        $this->requestStack = $requestStack;
        $this->cartRepository = $cartRepository;
        $this->security = $security;
    }
    //TODO Add here the user ID to find the card ok the logged user
    //https://ourcodeworld.com/articles/read/46/check-if-signed-user-have-a-specific-role-in-symfony-2-3
    public function getCart(): ?Order
    {
        //TODO add check if the user is connected
        $cart = $this->cartRepository->findOneBy([
            'id' => $this->getCartId(),
            'status' => Order::STATUS_CART
        ]);

        return $cart;
    }

    public function getCartUser($cartID): ?Order
    {
        $cart = $this->cartRepository->find($cartID);
        return $cart;
    }

    //TODO Add here the userID to set the cart
    public function setCart(Order $cart): void
    {
        $this->getSession()->set(self::CART_KEY_NAME, $cart->getId());
    }

    private function getCartId(): ?int
    {
        return $this->getSession()->get(self::CART_KEY_NAME);
    }

    private function getSession(): SessionInterface
    {
        return $this->requestStack->getSession();
    }
}
