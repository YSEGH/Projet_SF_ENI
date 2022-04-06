<?php

namespace App\Factory;

use App\Entity\Order;
use App\Entity\OrderItem;
use App\Entity\Article;

class OrderFactory
{

    public function create(): Order
    {
        $order = new Order();
        $order
            ->setStatus(Order::STATUS_CART)
            ->setCreatedAt(new \DateTime())
            ->setUpdatedAt(new \DateTime());

        return $order;
    }

    public function createItem(Article $article): OrderItem
    {
        $item = new OrderItem();
        $item->setProduct($article);
        $item->setQuantity(1);

        return $item;
    }
}