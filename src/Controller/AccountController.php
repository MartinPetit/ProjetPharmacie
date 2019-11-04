<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\AccountType;
use App\Form\RegistrationType;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\Security\Core\User\UserInterface;

class AccountController extends AbstractController
{
    /**
     * @Route("/login", name="account_login")
     */
    public function login(AuthenticationUtils $utils)
    {
        $error = $utils->getLastAuthenticationError();
        $username = $utils->getLastUsername();

        
        return $this->render('account/login.html.twig', [
            'hasError' =>$error !== null,
            'username' => $username
        ]);
    }

    /**
     * @Route("/logout", name="account_logout")
     */
    public function logout() {

    }

    /**
     * @Route("/register", name="account_register")
     */
    public function register(Request $request, ObjectManager $manager, UserPasswordEncoderInterface $encoder) {
        $user = new User;

        $form = $this->createForm(RegistrationType::class, $user);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $hash = $encoder->encodePassword($user, $user->getHash());
            $user->setHash($hash);

            $manager->persist($user);
            $manager->flush();

            
$this->addFlash(
                'success', 
                "Vous êtes enregistré, un mail vous a été envoyé"
              );
              return $this->redirectToRoute('account_login');
        }

        return $this->render('account/registration.html.twig', [
            'form' => $form->createView()
        ]);

    }





    /**
     * @Route("/account/profile", name = "account_profile")
     */


    public function profile(Request $request, ObjectManager $manager) {

        $user = $this->getUser();

        $form = $this->createForm(AccountType::class, $user);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $manager->persist($user);
            $manager->flush();

            $this->addFlash(
                'success', 
                "Vos modifications ont été prises en compte"
              );

              return $this->redirectToRoute('homepage');
        }





        return $this->render('account/profile.html.twig', [
            'form' => $form->createView()
        ]);

    }
}
