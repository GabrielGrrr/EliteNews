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

    /**
     * @Route("/rules", name="rules")
     */
    public function rules()
    {
        return $this->render('meta/rules.html.twig');
    }

    /**
     * @Route("/legal", name="legal")
     */
    public function legal()
    {
        return $this->render('meta/legal.html.twig');
    }

    public function notfound()
    {
        return $this->render('meta/notfound.html.twig');
    }

    /**
     * @Route("/registered", name="reg_success")
     */
    public function registered()
    {
        return $this->render('meta/reg_success.html.twig');
    }

    /**
     * @Route("/unregistered", name="unreg_success")
     */
    public function unregistered()
    {
        return $this->render('meta/unreg_success.html.twig');
    }
}
