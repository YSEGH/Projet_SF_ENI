<?php

namespace App\Entity;

use App\Repository\OrderItemRepository;
use Doctrine\ORM\Mapping as ORM;
use JetBrains\PhpStorm\Pure;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: OrderItemRepository::class)]
class OrderItem
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\ManyToOne(targetEntity: Article::class)]
    #[ORM\JoinColumn(nullable: false)]
    private $product;

    #[ORM\Column(type: 'integer')]
    #[Assert\NotBlank]
    #[Assert\GreaterThanOrEqual(1)]
    private $quantity;

    #[ORM\ManyToOne(targetEntity: Order::class, inversedBy: 'items')]
    #[ORM\JoinColumn(nullable: false)]
    private $orderRef;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getProduct(): ?Article
    {
        return $this->product;
    }

    public function setProduct(?Article $product): self
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

    public function getOrderRef(): ?Order
    {
        return $this->orderRef;
    }

    public function setOrderRef(?Order $orderRef): self
    {
        $this->orderRef = $orderRef;

        return $this;
    }

    #[Pure] public function equals(OrderItem $item): bool
    {
        return $this->getProduct()->getId() === $item->getProduct()->getId();
    }

    #[Pure] public function getTotal(): float
    {
        return $this->getProduct()->getPrice() * $this->getQuantity();
    }
}
