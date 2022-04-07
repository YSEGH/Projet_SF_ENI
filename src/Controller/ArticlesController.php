<?php

namespace App\Controller;

use App\Form\AddToCartType;
use App\Manager\CartManager;
use App\Entity\PropertySearch;
use App\Form\PropertySearchType;
use App\Repository\ArticleRepository;
use App\Repository\CategoryRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ArticlesController extends AbstractController
{
    #All items are listed in one single page
    #[Route('/boutique/{page}/{categorie}', name: 'app_list', requirements: ['page' => '\d+'])]
    public function list(Request $request, ArticleRepository $repo, CategoryRepository $cate_repo, $page = 0, $categorie = null, $min = null, $max = null): Response

    {
        $search = new PropertySearch();
        $search->setPage($page);
        $search->setCategorie($categorie);
        $search->setMin($min);
        $search->setMax($max);

        $form_range = $this->createForm(PropertySearchType::class, $search);
        $form_range->handleRequest($request);

        $items = $repo->getItems($search);
        $categories = [];
        foreach ($items['data'] as $item) {
            array_push($categories, $item->getCategory());
        }
        $categories = array_unique($categories);
        return $this->render('articles/boutique.html.twig', [
            'form_range' => $form_range->createView(),
            'items' => $items['paginator'],
            'categorie' => $categorie,
            'categories' => $categories,
            'min' => $search->getMin(),
            'max' => $search->getMax()
        ]);
    }

    /* #Old route for article detail
    #Display the detail of an item reach with his ID
    #[Route('/boutique/detail/{id}', name: 'app_detail_id', requirements: ['id' => '\d+'])]
    public function detail($id, ArticleRepository $repo): Response
    {
        $item = $repo->find($id);
        if (!$item)
            throw $this->createNotFoundException();
        return $this->render('articles/detail.html.twig', compact('item'));
    }
    */

    #Test new cart that persists in DB
    #[Route('/boutique/detail/{id}', name: 'app_detail_id', requirements: ['id' => '\d+'])]
    public function detail($id, ArticleRepository $repo, Request $request, CartManager $cartManager): Response
    {
        //TODO transférer des boutons add to cart et quantity dans la boutique
        $form = $this->createForm(AddToCartType::class);
        $article = $repo->find($id);
        if (!$article)
            throw $this->createNotFoundException();
        $form->handleRequest($request);
        //Gestion du retour du formulaire
        if ($form->isSubmitted() && $form->isValid()) {

            $item = $form->getData();
            $item->setProduct($article);

            $cart = $cartManager->getCurrentCart();
            $cart
                ->addItem($item)
                ->setUpdatedAt(new \DateTime());

            $cartManager->save($cart);


            return $this->redirectToRoute('app_cart');
        }

        return $this->render('articles/detail.html.twig', [
            'article' => $article,
            'form' => $form->createView()
        ]);
    }
}
