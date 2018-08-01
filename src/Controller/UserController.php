<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\LoginType;

use App\Form\RegisterType;
use App\Repository\UserRepository;

use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Persistence\ObjectManager;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserController extends Controller
{
    /**
     * @Route("/register", name="register")
     */
    public function register(Request $request, ObjectManager $manager, UserPasswordEncoderInterface $encoder)
    {
        $user = new User();
        $form = $this->createForm(RegisterType::class, $user);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            $user->setDateInscription(new \DateTime());
            if($user->getNewsletterSubscriber())$user->setDateSubscription(new \DateTime());
            $user->setModerationStatus(0);
            $user->setUserStatus(0);
            $hash = $encoder->encodePassword($user, $user->getPassword());
            $user->setPassword($hash);
            $manager->persist($user);
            $manager->flush();

            return $this->redirectToRoute('account');
        }

        return $this->render('user/register.html.twig', 
        ['registerform' => $form->createView()]);
    }

    /**
     * @Route("/login", name="login")
     */
    public function login()
    {
        return $this->render('user/login.html.twig');
    }

    /**
     * @Route("/logout", name="logout")
     */
    public function logout() {}

    /**
     * @Route("/account", name="account")
     */
    public function account()
    {
        return $this->render('user/account.html.twig');
    }

    /**
     * @Route("/renew_password", name="new_password")
     */
    public function renew_password()
    {
        return $this->render('  ');
    }
}