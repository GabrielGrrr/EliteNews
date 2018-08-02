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
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

class UserController extends Controller
{
    /**
     * @Route("/register", name="register")
     */
    public function register(Request $request, ObjectManager $manager, UserPasswordEncoderInterface $encoder, AuthorizationCheckerInterface $authChecker)
    {
        if($authChecker->isGranted('ROLE_USER')) 
            return $this->redirectToRoute('account');
        
        $user = new User();
        $form = $this->createForm(RegisterType::class, $user);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            $user->setDateInscription(new \DateTime());
            if($user->getNewsletterSubscriber())$user->setDateSubscription(new \DateTime());
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
    public function login(AuthorizationCheckerInterface $authChecker)
    {
        if($authChecker->isGranted('ROLE_USER')) 
            return $this->redirectToRoute('account');
        
        return $this->render('user/login.html.twig');
    }

    /**
     * @Route("/logout", name="logout")
     */
    public function logout() {}

    /**
     * @Route("/account", name="account")
     */
    public function account(AuthorizationCheckerInterface $authChecker)
    {
        if(!$authChecker->isGranted('ROLE_USER')) 
            return $this->redirectToRoute('login');
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
