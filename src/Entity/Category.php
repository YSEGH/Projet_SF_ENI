<?php

namespace App\Entity;

use App\Repository\CategoryRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CategoryRepository::class)]
class Category
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\OneToMany(mappedBy: 'categoryID', targetEntity: Article::class)]
    private $name;

    public function __construct()
    {
        $this->name = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return Collection<int, Article>
     */
    public function getName(): Collection
    {
        return $this->name;
    }

    public function addName(Article $name): self
    {
        if (!$this->name->contains($name)) {
            $this->name[] = $name;
            $name->setCategoryID($this);
        }

        return $this;
    }

    public function removeName(Article $name): self
    {
        if ($this->name->removeElement($name)) {
            // set the owning side to null (unless already changed)
            if ($name->getCategoryID() === $this) {
                $name->setCategoryID(null);
            }
        }

        return $this;
    }
}
