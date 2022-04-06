<?php

namespace App\Controller;

use App\Entity\Article;
use App\Form\AddToCartType;
use App\Repository\ArticleRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ArticlesController extends AbstractController
{
    #All items are listed in one single page
    #[Route('/boutique', name: 'app_list')]
    public function list(ArticleRepository $repo): Response
    {
        $items = $repo->findAll();
        return $this->render('articles/boutique.html.twig', compact('items'));
    }

    #Items are diplayed with pagination
    #[Route('/boutique/page/{page}', name: 'app_list_page', requirements: ['page' => '\d+'])]
    public function page(ArticleRepository $repo, $page = 0): Response
    {
        $maxItemByPage = 1;
        $items = $repo->findWithLimit($page, $maxItemByPage);
        return $this->render('articles/boutique.html.twig', compact('items', 'page'));
    }

    #Items are diplayed with pagination
    #[Route('/boutique/page/{page}/{filter}', name: 'app_list_filter', requirements: ['page' => '\d+', 'filter' => '[a-zA-Z]+'])]
    public function filter(ArticleRepository $repo, $page = 0, $filter = null): Response
    {
        $maxItemByPage = 10;
        $items = $repo->findWithFilter($page, $maxItemByPage, $filter);
        return $this->render('articles/boutique.html.twig', compact('items', 'page', 'filter'));
    }

    #Display the detail of an item reach with his ID
    #[Route('/boutique/detail/{id}', name: 'app_detail_id', requirements: ['id' => '\d+'])]
    public function detail($id, ArticleRepository $repo): Response
    {
        $items = $repo->find($id);
        if (!$items)
            throw $this->createNotFoundException();
        return $this->render('articles/detail.html.twig', compact('items'));
    }

    #Test new cart that persists in DB
    #[Route('/boutique/cart/{id}', name: 'app_cart_id', requirements: ['id' => '\d+'])]
    public function tocart($id, ArticleRepository $repo, Request $request, CartManager $cartManager): Response
    {
        $form = $this->createForm(AddToCartType::class);
        $items = $repo->find($id);
        if (!$items)
            throw $this->createNotFoundException();
        $form->handleRequest($request);
        //Gestion du retour du formulaire
        if ($form->isSubmitted() && $form->isValid()) {
            $item = $form->getData();
            $item->setProduct($items);

            $cart = $cartManager->getCurrentCart();
            $cart
                ->addItem($item)
                ->setUpdatedAt(new \DateTime());

            $cartManager->save($cart);

            return $this->redirectToRoute('app_cart_id', ['id' => $items->getId()]);
        }

        return $this->render('articles/tocart.html.twig', [
            'items' => $items,
            'form' => $form->createView()
        ]);
    }
}
