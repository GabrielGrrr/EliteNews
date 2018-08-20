<?php

namespace App\Controller;

use App\Repository\CategoryRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class CommentController extends Controller
{
    /**
     * @Route("/comment", name="comment")
     */
    public function index()
    {
        $categorepo = new CategoryRepository();
        return $this->render('comment/index.html.twig', [
            'controller_name' => 'CommentController',
            'categories' => $categorepo->findAll()
        ]);
    }

    /**
     * @Route("/ajax/comments", name="browse_comments")
     */
    public function browse_comments(Request $request)
    {
        $categorepo = new CategoryRepository();
        if ($request->isXmlHttpRequest()) {
            $request->query->get('showJson');
            $jsonData = array();

            return new JsonResponse($jsonData);        // POUR AJAX
        }
        $comments = $this->getRepository(Comment::class)->list($thread_id, $index);
    }
}
