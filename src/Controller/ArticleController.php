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
use App\Repository\CommentRepository;
use App\Repository\ArticleRepository;
use Symfony\Component\HttpFoundation\Request;

use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

define('COMMENTS_PAR_PAGE', '20');
define('ARTICLES_PAR_PAGE', '20');

class ArticleController extends Controller
{
    /**
     * @Route("/", name="home")
     * @Route("/browse/{index}", name="browse")
     */
    public function index(ArticleRepository $article_repo, $index = null)
    {
        $this->getUser()? $user = $this->getDoctrine()->getRepository(User::class)->findOneBy(['email' => $this->getUser()->getUsername()]): $user = NULL;
        $texhandler = new ContentHandler;

        $articles = $article_repo->listArticles(isset($index) && $index > 0 ? $index -1 : 0, ARTICLES_PAR_PAGE);
        return $this->render('articles/home.html.twig', [
            'controller_name' => 'ArticleController', 'articles' => $articles, 'texthandler' => $texhandler, 'articles_star' => $article_repo->getSlideArticles(),
            'user' => $user, 'pagenavigation' => [1, $index ? $index : 1, ceil($article_repo->getArticlePageCount())]
        ]);
    }

    /**
     * @Route("/articles/search", name="article_search")
     * @Route("/articles/search/{keyword}", name="article_find")
     * @Route("/articles/search/{keyword}/{categories}", name="article_thorough")
     */
    public function search(ArticleRepository $article_repo, Request $request, string $keyword = null, string $categories = null)
    {
        $a = $request->query->get('keyword');
        if($a) $keyword = $a;

        $this->getUser()? $user = $this->getDoctrine()->getRepository(User::class)->findOneBy(['email' => $this->getUser()->getUsername()]): $user = NULL;
        $texthandler = new ContentHandler;
        $form = $this->createForm(SearchArticleType::class);

        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {
            
            $data = $form->getData();
            $articles = $article_repo->search($data['keyword'], $texthandler->convertArrayOfEnum($data), $data['contentToo']);
        }
        else if (isset($keyword) || isset($categories))
            $articles = $article_repo->search($keyword, $categories ? [0 => $categories]: null);
        else
            $articles = $article_repo->listArticles(0, ARTICLES_PAR_PAGE);
            
        return $this->render('articles/search.html.twig', [
             'articles' => $articles, 'texthandler' => $texthandler, 'searchForm' => $form->createView(),
             'user' => $user
        ]);
    }

    /**
     * @Route("/lire/{id}", name="article_read")
     * @Route("/lire/{id}/browse/{index}", name="browse_comment")
     * @Route("/lire/{id}/com/{commentid}", name="edit_comment")
     */
    public function read(Article $article, $commentid = null, $index = 1, ArticleRepository $article_repo, Request $request, ObjectManager $manager)
    {
        $user = NULL; $commentcount = NULL; $form = NULL; 
        $edit = false;
        $texthandler = new ContentHandler;
        $comment_repo= $this->getDoctrine()->getRepository(Comment::class);

        if (!$article) return $this->redirectToRoute('home');
        if ($this->getUser()) {
            $user = $this->getDoctrine()->getRepository(User::class)->findOneBy(['email' => $this->getUser()->getUsername()]);
            $comment = $commentid ? $comment_repo->find($commentid) : new Comment();

            $form = $this->createForm(CommentType::class, $comment);
            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()) {
                if (!$comment->getId()) {
                    $this->denyAccessUnlessGranted('send', $comment);
                    $author = $user;
                    $date = new \DateTime();
                    $comment->setDateCreation($date);
                    $comment->setThread($article->getThread());
                    $comment->setLikeCounter(0);
                    $comment->setAuthor($author);
                }
                else
                {
                    $this->denyAccessUnlessGranted('edit', $comment);
                }
                    $comment->setContent($texthandler->secureAndParse($comment->getContent()));
                    $manager->persist($comment);
                    $manager->flush();
                    $form = $this->createForm(CommentType::class, new Comment());
                    $commentcount = $comment_repo->getCommentPageCount($article->getThread());
                    $index = ceil($commentcount / COMMENTS_PAR_PAGE);
            }
        }

        $comments = $comment_repo->list($article->getThread(), $index -1, COMMENTS_PAR_PAGE);
        $commentcount = $commentcount ? $commentcount : $comment_repo->getCommentPageCount($article->getThread());
        $previousnext = $article_repo->getPreviousNext($article->getId());

        return $this->render('articles/read.html.twig', [
            'article' => $article,
            'articles_star' => $article_repo->getSlideArticles(), 
            'commentform' => $form === NULL ? NULL : $form->createView(), 
            'previous' => $previousnext[0][0]['previous_row'],
             'next' => $previousnext[1][0]['next_row'],
             'user' => $user, 'texthandler' => $texthandler, 'comments' => $comments,
             'commentcount' => $commentcount,
             'commentnavigation' => 
             ['start' => 1, 
             'index' => $index ? $index : 1, 
             'end' => ceil($commentcount / COMMENTS_PAR_PAGE)]
        ]);
    }

    /**
     * @Route("/articles/rediger", name="create_article")
     * @Route("/articles/editer/{id}", name="edit_article")
     */
    public function formArticle(Article $article = null, Request $request, ObjectManager $manager)
    {
        $user = NULL;
        if($this->getUser()) { 
            $user = $this->getDoctrine()->getRepository(User::class)->findOneBy(['email' => $this->getUser()->getUsername()]);

            if (!$article) $article = new Article();
            $this->denyAccessUnlessGranted('send', $article);
            $form = $this->createForm(ArticleType::class, $article);
            $form->handleRequest($request);
            //$date, 0, $this->getDoctrine()->getRepository(Forum::class)->rootForum(), $article, $author, $article->getTitre()
            if ($form->isSubmitted() && $form->isValid()) {
                if (!$article->getId()) {
                    $date = new \DateTime();
                    $thread = new Sujet();
                    $thread->setArticle($article)->setAuthor($author)->setDateCreation($date)->setForum($this->getDoctrine()->getRepository(Forum::class)->rootForum())
                    ->setTitre($article->getTitre())->setViewcount(0);
                    $article->setDateCreation($date);
                    $article->setAuthor($user);
                    $article->setRemoved(0);
                    $article->setThread($thread);
                    $article->setViewcount(0);
                    $manager->persist($thread);
                }
                $this->denyAccessUnlessGranted('edit', $article);
                //VERIFIER LA CORRESPONDANCE DES AUTEURS AVANT LA VALIDATION DE LEDITION
                
                $manager->persist($article);
                $manager->flush();

                return $this->redirectToRoute('article_read', ['id' => $article->getId()]);
            }
        }

        return $this->render('articles/create.html.twig', ['redacform' => $form->createView(), 'editmode' => $article->getId() ? true : false, 'user' => $user ]);
    }

    /**
     * @Route("/articles/effacer/{id}", name="remove_article")
     */
    public function removeArticle(Article $article, ObjectManager $manager)
    {
        $this->denyAccessUnlessGranted('edit', $article);
        $article->setRemoved(true); // No it's not ah ah
        $manager->persist($article);
        $manager->flush();

        return $this->redirectToRoute('home');
    }

    /**
     * @Route("/comment/effacer/{id}/{commentid}", name="remove_comment")
     */
    public function removeComment($id, $commentid, ObjectManager $manager)
    {
        $comment = $this->getDoctrine()->getRepository(Comment::class)->find($commentid);
        $this->denyAccessUnlessGranted('edit', $comment);
        $manager->remove($comment);
        $manager->flush();
        return $this->redirectToRoute('article_read', [ 'id' => $id]);
    }
}
