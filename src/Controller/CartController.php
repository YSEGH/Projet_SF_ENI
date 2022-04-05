<?php

namespace App\Controller;

use App\Repository\ArticleRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;

class CartController extends AbstractController
{
    //The cart
    #[Route('/cart', name: 'app_cart')]
    public function index(SessionInterface $session, ArticleRepository $repo): Response
    {
        $panier = $session->get('cart',[]); //récupère le panier de la session
        $cartData=[]; //infos du panier
        $total = 0; //prix total

        foreach($panier as $id => $quantity){
            $product = $repo->find($id);
            $cartData[] = [
                'product' => $product,
                'quantity' => $quantity
            ];
            $total += $product->getPrice()*$quantity;
        }

        return $this->render('cart/index.html.twig',compact('cartData','total'));
    }
    //Add to cart an item
    #[Route('/cart/add/{id}', name: 'app_cart_add', requirements: ['id' => '\d+'])]
    public function add($id, SessionInterface $session, ArticleRepository $repo): Response
    {
        $cart = $session->get('cart',[]); //récupérer le panier
        if(!empty($cart[$id])){
            $cart[$id]++;
        }else{
            $cart[$id] = 1;
        }
        $session->set('cart',$cart);//sauvegarde la panier en session
        return $this->redirectToRoute('app_cart');
    }

    //Remove to cart an item
    #[Route('/cart/remove/{id}', name: 'app_cart_remove', requirements: ['id' => '\d+'])]
    public function remove($id, SessionInterface $session, ArticleRepository $repo): Response
    {
        $cart = $session->get('cart',[]); //récupérer le panier
        if(!empty($cart[$id])){
            if($cart[$id] > 1){
                $cart[$id]--;
            }else{
                unset($cart[$id]);
            }
        }
        $session->set('cart',$cart);//sauvegarde la panier en session
        return $this->redirectToRoute('app_cart');
    }
}