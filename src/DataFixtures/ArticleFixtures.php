<?php

namespace App\DataFixtures;

use App\Entity\Article;
use App\Entity\Category;
use App\Repository\CategoryRepository;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\String\Slugger\AsciiSlugger;

class ArticleFixtures extends Fixture implements DependentFixtureInterface
{


    public function load(ObjectManager $manager): void
    {
        for ($i = 1; $i <= 10; $i++) {  //Crée 10 tongs
            $product = new Article();   //Crée l'article
            $slugger = new AsciiSlugger();
            $name = 'Tong ' . $i;
            $slug = $slugger->slug($name);
            $product                    //Set les paramètres
            ->setName($name)
                ->setSlug($slug)
                ->setDescription('Avec Tong ' . $i . ' DingTongs, ayez toujours un air de vacances !')
                ->setCategory($this->getReference(CategoryFixtures::CATEGORY_COLOR[mt_rand(1,count(CategoryFixtures::CATEGORY_COLOR))]))
                ->setPrice(mt_rand(8, 50))
                ->setImage('img/articles/tong_' . $i . '.jpg');

            $manager->persist($product);    //persist

            $manager->flush();              //exécute la requete
        }
    }

    public function getDependencies()
    {
        return array(
            CategoryFixtures::class,
        );
    }
}
