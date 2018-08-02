<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Forum;

//Manipulated entities
use App\Entity\Article;
use App\Entity\Comment;
use App\Form\ArticleType;
use App\Form\CommentType;
use App\Entity\Thread as Sujet;
use App\Form\SearchArticleType;
use App\Service\ContentHandler;

use App\Repository\UserRepository;
use App\Repository\ArticleRepository;
use Symfony\Component\HttpFoundation\Request;

use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

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
     * @Route("/articles/search/{keyword}/{category}", name="article_find2")
     * @Route("/articles/search/{keyword}/{category}/{content}", name="article_find3")
     */
    public function search(ArticleRepository $article_repo, Request $request, string $keyword = null, string $categories = null, bool $contentToo = null)
    {
        $texthandler = new ContentHandler;
        $form = $this->createForm(SearchArticleType::class);

        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            $articles = $article_repo->search($data['keywords'], $texthandler->convertArrayOfEnum($data), $data['contentToo']);
        }
        else if (isset($keyword) || isset($categories))
            $articles = $article_repo->search($keyword, $categories, $contentToo);
        else
            $articles = $article_repo->listArticles();
            
        return $this->render('articles/search.html.twig', [
             'articles' => $articles, 'texthandler' => $texthandler, 'searchForm' => $form->createView()
        ]);
    }

    /**
     * @Route("/read/{id}", name="article_read")
     */
    public function read(Article $article, ArticleRepository $article_repo, Request $request, ObjectManager $manager)
    {

        if (!$article) return $this->render('articles/home.html.twig');
        if ($this->getUser()) {

            $comment = new Comment();
            $form = $this->createForm(CommentType::class, $comment);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                if (!$comment->getId()) {
                    $author = $this->getDoctrine()->getRepository(User::class)->findOneBy(['email' => $this->getUser()->getUsername()]);
                    $date = new \DateTime();
                    $comment->setDateCreation($date);
                    $comment->setThread($article->getThread());
                    $comment->setLikeCounter(0);
                    $comment->setAuthor($author);
                }

                $manager->persist($comment);
                $manager->flush();
            }
            $previousnext = $article_repo->getPreviousNext($article->getId());
            return $this->render('articles/read.html.twig', [
            'article' => $article, 'comments' => $article->getThread()->getComments(),
            'articles_star' => $article_repo->getSlideArticles(), 'commentform' => $form->createView(), 'previous' => $previousnext[0][0]['previous_row'],
             'next' => $previousnext[1][0]['next_row']]);
        }

        $previousnext = $article_repo->getPreviousNext($article->getId());
        return $this->render('articles/read.html.twig', [
            'article' => $article, 'comments' => $article->getThread()->getComments(),
            'articles_star' => $article_repo->getSlideArticles(), 'commentform' => NULL, 'previous' => $previousnext[0][0]['previous_row'],
             'next' => $previousnext[1][0]['next_row']
        ]);
    }

    /**
     * @Route("/admin/rediger", name="article_create")
     * @Route("/admin/editer/{id}", name="article_edit")
     */
    public function formArticle(Article $article = null, Request $request, ObjectManager $manager)
    {
        if ($this->getUser()) {
            if (!$article) $article = new Article();

            $form = $this->createForm(ArticleType::class, $article);
            $form->handleRequest($request);
            //$date, 0, $this->getDoctrine()->getRepository(Forum::class)->rootForum(), $article, $author, $article->getTitre()
            if ($form->isSubmitted() && $form->isValid()) {
                if (!$article->getId()) {
                    $date = new \DateTime();
                    $author = $this->getDoctrine()->getRepository(User::class)->findOneBy(['email' => $this->getUser()->getUsername()]);
                    $thread = new Sujet();
                    $thread->setArticle($article)->setAuthor($author)->setDateCreation($date)->setForum($this->getDoctrine()->getRepository(Forum::class)->rootForum())
                    ->setTitre($article->getTitre())->setViewcount(0);
                    $article->setDateCreation($date);
                    $article->setAuthor($author);
                    $article->setRemoved(0);
                    $article->setThread($thread);
                    $article->setViewcount(0);
                    $manager->persist($thread);
                }
                //VERIFIER LA CORRESPONDANCE DES AUTEURS AVANT LA VALIDATION DE LEDITION
                
                $manager->persist($article);
                $manager->flush();

                return $this->redirectToRoute('article_read', ['id' => $article->getId()]);
            }
        }

        return $this->render('articles/create.html.twig', ['redacform' => $form->createView(), 'editmode' => $article->getId() ? true : false]);
    }

    /**
     * @Route("/admin/remove/{id}", name="article_remove")
     */
    public function remove(Article $article, ObjectManager $manager)
    {
        $manager->remove($article);
        $manager->flush();

        return $this->render('articles/home.html.twig');
    }

    /**
     * @Route("/thread/{id}/comment/", name="comment")
     */
    public function comment(Article $article, ArticleRepository $article_repo, Request $request, ObjectManager $manager)
    {
        return $this->render('articles/remove.html.twig');
    }
}
