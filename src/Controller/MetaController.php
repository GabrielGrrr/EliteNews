<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\ContactType;
use App\Entity\ContactMessage;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class MetaController extends Controller
{

    /**
     * @Route("/admin", name="admin")
     */
    public function admin()
    {
    }


    /**
     * @Route("/about", name="about")
     */
    public function about()
    {
        $this->getUser()? $user = $this->getDoctrine()->getRepository(User::class)->findOneBy(['email' => $this->getUser()->getUsername()]): $user = NULL;
        return $this->render('meta/about.html.twig', ['user' => $user] );
    }

    /**
     * @Route("/contact", name="contact")
     */
    public function contact(ContactMessage $message = null, Request $request, ObjectManager $manager)
    {
        $message ? $message : $message = new ContactMessage();
        $confirmMode = false;
        $form = $this->createForm(ContactType::class, $message);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            $manager->persist($message);
            $manager->flush();
            $confirmMode = true;
        }

        $this->getUser()? $user = $this->getDoctrine()->getRepository(User::class)->findOneBy(['email' => $this->getUser()->getUsername()]): $user = NULL;
        return $this->render('meta/contact.html.twig', ['user' => $user, 'contactform' => $form->createView(),
        'confirmMode' => $confirmMode] );
    }

    /**
     * @Route("/projects", name="projects")
     */
    public function projects()
    {
        $this->getUser()? $user = $this->getDoctrine()->getRepository(User::class)->findOneBy(['email' => $this->getUser()->getUsername()]): $user = NULL;
        return $this->render('meta/projects.html.twig', ['user' => $user] );
    }

    /**
     * @Route("/rules", name="rules")
     */
    public function rules()
    {
        $this->getUser()? $user = $this->getDoctrine()->getRepository(User::class)->findOneBy(['email' => $this->getUser()->getUsername()]): $user = NULL;
        return $this->render('meta/rules.html.twig', ['user' => $user] );
    }

    /**
     * @Route("/legal", name="legal")
     */
    public function legal()
    {
        $this->getUser()? $user = $this->getDoctrine()->getRepository(User::class)->findOneBy(['email' => $this->getUser()->getUsername()]): $user = NULL;
        return $this->render('meta/legal.html.twig', ['user' => $user] );
    }

    public function notfound()
    {
        $this->getUser()? $user = $this->getDoctrine()->getRepository(User::class)->findOneBy(['email' => $this->getUser()->getUsername()]): $user = NULL;
        $this->getUser()? $user = $this->getDoctrine()->getRepository(User::class)->findOneBy(['email' => $this->getUser()->getUsername()]): $user = NULL;
        return $this->render('meta/notfound.html.twig', ['user' => $user] );
    }

    /**
     * @Route("/registered", name="reg_success")
     */
    public function registered()
    {
        $this->getUser()? $user = $this->getDoctrine()->getRepository(User::class)->findOneBy(['email' => $this->getUser()->getUsername()]): $user = NULL;
        return $this->render('meta/reg_success.html.twig', ['user' => $user] );
    }

    /**
     * @Route("/unregistered", name="unreg_success")
     */
    public function unregistered()
    {
        $this->getUser()? $user = $this->getDoctrine()->getRepository(User::class)->findOneBy(['email' => $this->getUser()->getUsername()]): $user = NULL;
        return $this->render('meta/unreg_success.html.twig', ['user' => $user] );
    }
}
