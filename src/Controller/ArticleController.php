<?php

namespace App\Controller;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

//Manipulated entities
use App\Entity\Article;
use App\Form\ArticleType;
use App\Repository\ArticleRepository;
use App\Service\ContentHandler;

use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\HttpFoundation\Request;

use App\Entity\Thread;

class ArticleController extends Controller
{
    /**
     * @Route("/", name="home")
     */
    public function index(ArticleRepository $article_repo)
    {
        $texhandler = new ContentHandler;
        $articles = $article_repo->listArticles();
        return $this->render('articles/home.html.twig', [
            'controller_name' => 'ArticleController', 'articles' => $articles, 'texthandler' => $texhandler, 'articles_star' => $article_repo->getSlideArticles()
        ]);
    }

    /**
     * @Route("/articles/search", name="article_search")
     * @Route("/articles/search/{keyword}", name="article_find")
     */
    public function search(string $keyword = null, ArticleRepository $article_repo)
    {
        $texhandler = new ContentHandler;
        $articles = $article_repo->listArticles();
        return $this->render('articles/search.html.twig', [
            'controller_name' => 'ArticleController', 'articles' => $articles, 'texthandler' => $texhandler
        ]);
    }

    /**
     * @Route("/read/{id}", name="article_read")
     */
    public function read(Article $article, ArticleRepository $article_repo)
    {
        return $this->render('articles/read.html.twig', ['article' => $article, 'comments' => $article->getThread()->getComments(), 'articles_star' => $article_repo->getSlideArticles()]);
    }

    /**
     * @Route("/rediger", name="article_create")
     * @Route("/editer/{id}", name="article_edit")
     */
    public function formArticle(Article $article = null, Request $request, ObjectManager $manager)
    {
        if(!$article) $article = new Article();

        $form = $this->createForm(ArticleType::class, $article);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            if(!$article->getId()) {
            $date = new \DateTime();
            $article->setDateCreation($date); 
            $article->setRemoved(0);
            $article->setThread(new Thread($date, 0, $articleForum, $article, $author, $article->getTitre()));
            $article->setViewcount(0); }

            $manager->persist($article);
            $manager->flush();

            return $this->redirectToRoute('read', ['id' => $article->getId()]);
        }

        return $this->render('articles/create.html.twig', ['redacform' => $form->createView(), 'editmode' => $article->getId() ? TRUE : FALSE]);
    }

    /**
     * @Route("/articles/remove/{id}", name="article_remove")
     */
    public function remove()
    {
        return $this->render('articles/remove.html.twig');
    }

    /**
     * @Route("/thread/{id}/comment/", name="comment")
     */
    public function comment()
    {
        return $this->render('articles/read.html.twig');
    }
}
