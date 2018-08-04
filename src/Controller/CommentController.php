<?php

namespace App\Controller;

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
        return $this->render('comment/index.html.twig', [
            'controller_name' => 'CommentController',
        ]);
    }

    /**
     * @Route("/ajax/comments", name="browse_comments")
     */
    public function browse_comments(Request $request)
    {
        if ($request->isXmlHttpRequest()) { 
            $request->query->get('showJson');
            $jsonData = array();  
            
            return new JsonResponse($jsonData);        // POUR AJAX
        }
        $comments = $this->getRepository(Comment::class)->list($thread_id, $index);
    }
}
