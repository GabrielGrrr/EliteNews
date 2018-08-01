<?php

namespace App\Controller;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class MetaController extends Controller
{
    /**
     * @Route("/about", name="about")
     */
    public function about()
    {
        return $this->render('meta/about.html.twig');
    }

    /**
     * @Route("/contact", name="contact")
     */
    public function contact()
    {
        return $this->render('meta/contact.html.twig');
    }

    /**
     * @Route("/projects", name="projects")
     */
    public function projects()
    {
        return $this->render('meta/projects.html.twig');
    }
}
