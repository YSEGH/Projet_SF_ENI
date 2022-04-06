<?php

namespace App\DataFixtures;

use App\Entity\Category;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class CategoryFixtures extends Fixture
{
    //public const CATEGORY_COLOR = 'Rose';
    public const CATEGORY_COLOR = array(1 => 'Rose', 'Rouge', 'Vert', 'Bleu', 'Jaune');
    public function load(ObjectManager $manager): void
    {
        for($i=1;$i<=count(self::CATEGORY_COLOR);$i++){
            $category = new Category();
            $this->addReference(self::CATEGORY_COLOR[$i],$category);
            $category->setName(self::CATEGORY_COLOR[$i]);
            $manager->persist($category);
            $manager->flush();
        }

    }
}
