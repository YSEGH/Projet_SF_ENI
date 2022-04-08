<?php

namespace App\Controller;

use App\Form\CartType;
use App\Manager\CartManager;
use App\Repository\ArticleRepository;
use App\Repository\OrderItemRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Routing\Annotation\Route;

class CartController extends AbstractController
{
    #[Route('/cart', name: 'app_cart')]
    public function index(CartManager $cartManager, Request $request, OrderItemRepository $orderItemRepository, Session $session, ArticleRepository $articleRepository): Response
    {
        $cart = $cartManager->getCurrentCart();
        $form = $this->createForm(CartType::class, $cart);

        //Traitement du formulaire
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $cart->setUpdatedAt(new \DateTime());
            $cartManager->save($cart);

            return $this->redirectToRoute('app_cart');
        }

        $nbitemsofcart = $orderItemRepository->findBy(['orderRef'=>$cart->getId()]);
        $nbitems = 0;
        $nbref = 0;
        $img = [];

        foreach ($nbitemsofcart as $nbi){
            $nbitems += $nbi->getQuantity();
            $articles[] = $nbi->getProduct();
            $nbref++;
        }
        $session->set('nbItemCart',$nbref);

        if(!empty($articles)){
            foreach ($articles as $article){
                $img[] = $article->getImage();
            }
        }


        return $this->render('cart/index.html.twig', [
            'cart' => $cart,
            'form' => $form->createView(),
            'nbitems' => $nbitems,
            'nbref' => $nbref,
            'img' => $img
        ]);
    }
}
