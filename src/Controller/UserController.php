<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\LoginType;

use App\Form\RegisterType;
use App\Form\UserAccountType;

use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\Request;

use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
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

            return $this->redirectToRoute('reg_success');
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
    public function account(AuthorizationCheckerInterface $authChecker, Request $request, ObjectManager $manager)
    {
        if(!$authChecker->isGranted('ROLE_USER')) 
            return $this->redirectToRoute('login');

        $user = $this->getDoctrine()->getRepository(User::class)->findOneBy(['email' => $this->getUser()->getUsername()]);
        $form = $this->createForm(UserAccountType::class, $user);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $manager->persist($user);
            $manager->flush();
        }
        $commentcount = $this->getDoctrine()->getRepository(User::class)->getCommentCount($user);
        return $this->render('user/account.html.twig', ['user' => $user, 'accountForm' => $form->createView(),
         "commentCount" => $commentcount? $commentcount[0]['commentcount'] : 0 ]);
    }

    /**
     * @Route("/renew_password", name="new_password")
     */
    public function renew_password()
    {
        return $this->render('  ');
    }

    /**
     * @Route("/remove_account/{id}", name="remove_user")
     */
    public function remove_user($id, ObjectManager $manager, Request $request)
    {
        if(!$this->isGranted('ROLE_USER')) 
            return $this->redirectToRoute('login');
        
        $user = $this->getDoctrine()->getRepository(User::class)->findOneBy(['email' => $this->getUser()->getUsername()]);
        if($user == $this->getDoctrine()->getRepository(User::class)->findOneBy(['id' => $id]))
        {
            $request->getSession()->invalidate();
            $manager->remove($user);
            $manager->flush();
            return $this->redirectToRoute('unreg_success');
        }
        else throw new \AccessDeniedHttpException ('Vous n\'avez pas de droits sur ce compte.');
    }
}
