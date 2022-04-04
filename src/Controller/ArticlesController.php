<?php

namespace App\Controller;

use App\Repository\ArticleRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ArticlesController extends AbstractController
{
    #All items are listed in one single page
    #[Route('/list', name: 'app_list')]
    public function index(): Response
    {
        return $this->render('articles/index.html.twig');
    }
    #Items are diplayed with pagination
    #[Route('/list/page/{page}', name: 'app_list_page', requirements:['page'=>'\d+'])]
    public function page($page = 0,ArticleRepository $repo): Response
    {
        $maxItemByPage = 10;
        $offset = $page * $maxItemByPage;
        $nbItem= $repo->countItems();
        $lastPage = intdiv($nbItem,$maxItemByPage);

        if($nbItem % $maxItemByPage === 0){
            $lastPage--;
        }
        $items = $repo->findBy(['dateCreated'=>'DESC'], $maxItemByPage, $offset);

        return $this->render('articles/pages.html.twig',compact('items','page','lastPage'));
    }

    #Display the detail of an item reach with his ID
    #[Route('/detail/{id}', name: 'app_detail_id', requirements:['id'=>'\d+'])]
    public function detail($id, ArticleRepository $repo): Response
    {
        $article=$repo->find($id);
        if(!$article)
            throw $this->createNotFoundException();
        return $this->render('articles/detail.html.twig',compact('article'));
    }
}
