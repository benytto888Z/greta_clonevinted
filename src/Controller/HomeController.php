<?php

namespace App\Controller;

use App\Entity\Article;
use App\Repository\ArticleRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    /**
     * @Route("/", name="app_accueil")
     */
    public function index(): Response
    {
        return $this->render('home/accueil.html.twig');
    }

    /**
     * @Route("/articles", name="articles")
     */
    public function articles(ArticleRepository $repo)
    {
        $user = $this->getUser();
        $articles = $repo->findBy(['actif' => 1]);
        // dd($articles);
        // $articles = $repo->findArticleByPriceLessThan($prix);

        return $this->render('home/articles.html.twig', compact('articles', 'user'));
    }

    /**
     * @Route("/article/{slug}", name="article_details")
     */
    public function articleDetails(ArticleRepository $repo, $slug)
    {
        $user = $this->getUser();
        $article = $repo->findOneBy(['slug' => $slug]);

        return $this->render('home/articledetails.html.twig', compact('article', 'user'));
    }

    /**
     * @Route("/user/articles_search", name="user_articles_search")
     */
    public function searchArticle(EntityManagerInterface $em, Request $request, ArticleRepository $repo): Response
    {
        //dd($request);
        // dd($request->request);
        $data = $request->request->all();
        // dd($data);
        $mot = $data['motrecherche'];
        $articles = $repo->filterArticlesBy($mot);
        // dd($articles);

        return $this->render('home/articles.html.twig', compact('articles'));
    }
}
