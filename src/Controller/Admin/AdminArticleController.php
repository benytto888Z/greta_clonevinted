<?php

namespace App\Controller\Admin;

use App\Entity\Article;
use App\Entity\Categorie;
use App\Entity\User;
use App\Form\ArticleFormType;
use App\Form\CategorieFormType;
use App\Repository\ArticleRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminArticleController extends AbstractController
{
    /**
     * @Route("/admin/article", name="admin_affichage_articles")
     */
    public function index(ArticleRepository $repo): Response
    {
        $admin = $this->getUser();
        $articles = $repo->findAll();

        return $this->render('admin/admin_article/affichagearticles.html.twig', compact('admin', 'articles'));
    }

    /**
     * @Route("/admin/categorie/ajout", name="categorie_ajout")
     */
    public function ajouterCategorie(Categorie $categorie = null, Request $request, EntityManagerInterface $em): Response
    {
        if (!$categorie) {
            $categorie = new Categorie();
        }

        $form = $this->createForm(CategorieFormType::class, $categorie);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($categorie);
            $em->flush();
        }

        return $this->render('admin/admin_article/ajoutcategorie.html.twig', ['formulaireCateg' => $form->createView()]);
    }

    /**
     * @Route("/admin/article/create", name="admin_article_create")
     * @Route("/admin/article/{id}", name="admin_article_edit", methods = "GET|POST")
     */
    public function editCreateArticle(Article $article = null, Request $request, EntityManagerInterface $em)
    {
        $admin = $this->getUser();
        if (!$article) {
            $article = new Article();
            $article->setUser($this->getUser());
        }
        //variable pour savoir si on est en création ou modification
        $modif = $article->getId() === null;

        $form = $this->createForm(ArticleFormType::class, $article);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($article);
            $em->flush();

            $this->addFlash('message', $modif ? 'Article ajouté avec succès ' : 'Article modifé avec succès');

            return $this->redirectToRoute('admin_affichage_articles');
        }
        $editArticleForm = $form->createView();

        return $this->render('admin/admin_article/editcreatearticle.html.twig', compact('admin', 'article', 'editArticleForm', 'modif'));
    }

    /**
     * @Route("/admin/article/{id}", name="admin_article_delete", methods="SUP")
     */
    public function deleteArticle(Article $article = null, Request $request, EntityManagerInterface $em)
    {
        //  dd($request->get('_token'));
        if ($this->isCsrfTokenValid('SUP'.$article->getId(), $request->get('_token'))) {
            $em->remove($article);
            $em->flush();
            $this->addFlash('message', 'Article supprimé avec succès');

            return $this->redirectToRoute('admin_affichage_articles');
        }
    }

    //////////////////////////  Tests symfony //////////////

    /**
     * @Route("/user/article/ajout", name="user_article_ajout")
     */
    public function ajoutArticle(EntityManagerInterface $em): Response
    {
        //$em = $this->getDoctrine()->getManager();
        //$currentUser = $this->getUser();

        $c2 = new Categorie();
        $c2->setNom('Divers');
        $em->persist($c2);

        $u2 = new User();
        $u2->setEmail('kitkat@yahoo.fr');
        $u2->setPassword('romeojuliette');
        $u2->setRoles(['ROLE_ADMIN']);
        $em->Persist($u2);

        $a2 = new Article();
        $a2->setTitre('sacoche');
        $a2->setDescription('sacoche cool');
        $a2->setPrix(155);
        $a2->setImage('sacoche.jpeg');
        $a2->setActif(1);

        $a2->addCategorie($c2);
        $a2->setUser($u2);

        $em->Persist($a2);
        $em->flush();

        return new Response('Article ajouté : '.$a2->getTitre());
    }

    /**
     * @Route("/user/articles/{id}", name="user_articles")
     */
    public function getArt(Article $article): Response
    {
        //$em = $this->getDoctrine()->getManager();
        //$repo = $em->getRepository(Article::class);
        // $article = $repo->find($id);
        // $article = $repo->findOneBy(['slug' => $slug]);
        // parameter converter

        dd($article);

        return new Response('Article recupéré : '.$article->getTitre());
    }

    /**
     * @Route("/user/articles_prix/{prix}", name="user_articles_prix")
     */
    public function getArtPrix($prix, ArticleRepository $repo): Response
    {
        $articles = $repo->findArticleByPriceLessThan($prix);
        dd($articles);

        return new Response('Article recupéré : ');
    }

    /*
     * @Route("/user/articles_search/{mot}", name="user_articles_search")
     */
   /* public function searchArticle($mot, EntityManagerInterface $em): Response
    {
        $repo = $em->getRepository(Article::class);
        $articles = $repo->filterArticleBy($mot);
        dd($articles);

        return new Response('Liste des article correspondants');
    }*/
}
