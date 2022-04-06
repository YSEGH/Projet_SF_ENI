<?php

namespace App\Controller;

use App\Entity\PropertySearch;
use App\Form\PropertySearchType;
use App\Form\RangeFormType;
use App\Repository\ArticleRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ArticlesController extends AbstractController
{
    #All items are listed in one single page
    #[Route('/boutique/{page}/{categorie}', name: 'app_list')]
    public function list(Request $request, ArticleRepository $repo, $page = 0, $categorie = null, $min = null, $max = null): Response
    {
        $search = new PropertySearch();
        $search->setPage($page);
        $search->setCategorie($categorie);
        $search->setMin($min);
        $search->setMax($max);

        $form_range = $this->createForm(PropertySearchType::class, $search);
        $form_range->handleRequest($request);

        $items = $repo->getItems($search);

        return $this->render('articles/boutique.html.twig', [
            'form_range' => $form_range->createView(),
            'items' => $items,
            'categorie' => $categorie,
            'min' => $search->getMin(),
            'max' => $search->getMax()
        ]);
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
}
