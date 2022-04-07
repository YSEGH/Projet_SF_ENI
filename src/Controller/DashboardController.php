<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/mon-compte')]
class DashboardController extends AbstractController
{
    #[Route('/infos', name: 'app_account_infos')]
    public function infos(): Response
    {
        return $this->render('dashboard/infos.html.twig', [
            'controller_name' => 'Infos personnelles',
        ]);
    }

    #[Route('/mes-adresses', name: 'app_account_address')]
    public function address(): Response
    {
        return $this->render('dashboard/address.html.twig', [
            'controller_name' => 'Mes adresses',
        ]);
    }

    #[Route('/mes-commandes', name: 'app_account_orders')]
    public function orders(): Response
    {
        return $this->render('dashboard/orders.html.twig', [
            'controller_name' => 'Mes commandes',
        ]);
    }
}
