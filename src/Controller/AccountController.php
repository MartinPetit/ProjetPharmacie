<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\AccountType;
use App\Entity\PasswordUpdate;
use App\Form\ForgottenPasswordType;
use App\Form\RegistrationType;
use App\Form\PasswordUpdateType;
use App\Form\ResetPasswordType;
use App\Repository\UserRepository;
use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Csrf\TokenGenerator\TokenGeneratorInterface;

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
            'hasError' => $error !== null,
            'username' => $username
        ]);
    }

    /**
     * @Route("/logout", name="account_logout")
     */
    public function logout()
    { }

    /**
     * @Route("/register", name="account_register")
     */
    public function register(Request $request, ObjectManager $manager, UserPasswordEncoderInterface $encoder)
    {
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
     * @IsGranted("ROLE_USER")
     */


    public function profile(Request $request, ObjectManager $manager)
    {

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

    /**
     * @Route("/account/password-update", name = "account_password")
     * @IsGranted("ROLE_USER")
     */


    public function updatePassword(Request $request, UserPasswordEncoderInterface $encoder, ObjectManager $manager)
    {

        $passwordUpdate = new PasswordUpdate();

        $user = $this->getUser();

        $form = $this->createForm(PasswordUpdateType::class, $passwordUpdate);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            //Verifier que le oldPassword du formulaire soit le même que le mot de passe actuel de l'user
            if (!password_verify($passwordUpdate->getOldPassword(), $user->getHash())) {
                //gerer l'erreur
                $form->get('oldPassword')->addError(new FormError("Le mot de passe que vous avez rentré n'est pas votre mot de passe actuel"));
            } else {
                $newPassword = $passwordUpdate->getNewPassword();
                $hash = $encoder->encodePassword($user, $newPassword);

                $user->setHash($hash);

                $manager->persist($user);

                $manager->flush();

                $this->addFlash(
                    'success',
                    'Votre mot de passe a bien été modifié'
                );

                return $this->redirectToRoute('homepage');
            }
        }

        return $this->render('account/password.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/forgottenPassword", name="app_forgotten_password")
     */
    public function forgottenPassword(Request $request, UserPasswordEncoderInterface $encoder, \Swift_Mailer $mailer,TokenGeneratorInterface $tokenGenerator, UserRepository $repo, ObjectManager $manager)
    {

        $form = $this->createForm(ForgottenPasswordType::class);
            $form->handleRequest($request);

        if ($request->isMethod('POST')) {


            if ($form->isSubmitted() && $form->isValid()) {
                $email = $form->getData();
                

            $user = $repo->findOneByEmail($email);
            /* @var $user User */
            $nom = $user->getFirstname();

            if ($user === null) {
                $this->addFlash('danger', 'Email Inconnu');
                return $this->redirectToRoute('homepage');
            }
            $token = $tokenGenerator->generateToken();


                $user->setResetToken($token);
                $manager->flush();

            $url = $this->generateUrl('app_reset_password', array('token' => $token), UrlGeneratorInterface::ABSOLUTE_URL);

            $message = (new \Swift_Message('Mot de passe oublié'))
                ->setFrom('martinpetit1998@gmail.com')
                ->setTo($user->getEmail())
                ->setBody(
                    "Bonjour " .$nom. " veuillez cliquer sur ce lien pour réinitialiser votre mot de passe : " . $url,
                    'text/html'
                );

            $mailer->send($message);

            $this->addFlash('success', 'Un mail vous a été envoyé, veuillez vérifier votre boite mail !');

            return $this->redirectToRoute('homepage');
        }
    }

        return $this->render('account/forgotten_password.html.twig',[
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/reset_password/{token}", name="app_reset_password")
     */
    public function resetPassword(Request $request, string $token, UserPasswordEncoderInterface $passwordEncoder, UserRepository $repo, ObjectManager $manager)
    {

        $form = $this->createForm(ResetPasswordType::class);

        $form->handleRequest($request);


        
        if ($form->isSubmitted() && $form->isValid()) {

            $mdp = $form->get('password')->getData();

        if ($request->isMethod('POST')) {

            
            

            $user = $repo->findOneByResetToken($token);
            /* @var $user User */

            if ($user === null) {
                $this->addFlash('danger', 'Token Inconnu');
                return $this->redirectToRoute('homepage');
            }


            $user->setResetToken(null);
            $user->setHash($passwordEncoder->encodePassword($user, $mdp));
            $manager->flush();

            $this->addFlash('success', 'Mot de passe mis à jour');

            return $this->redirectToRoute('account_login');
        } }else {

            return $this->render('account/reset_password.html.twig', [
                'token' => $token,
                'form' => $form->createView()
                ]);
        }

    }



}
