<?php

namespace App\Controller;


use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class homeController extends AbstractController
{

    /**
     * @Route("/", name="homepage")
     * Page d'accueil
     */

    public function home()
    {
        return $this->render('Home/home.html.twig', [
            'title' => "Pharmacie Pasteur",
        ]);
    }


    /**
     * Permet d'avoir la configuration de base d'un champ
     * 
     * @param string $label
     * @param string $placeholder
     * @return array
     */

    private function getConfiguration($label, $placeholder)
    {
        return  [
            'label' => $label,
            'attr' => [
                'placeholder' => $placeholder
            ]
        ];
    }



    /**
     * @Route("/", name="homepage")
     * Création du formulaire de contact avec envoi de mail
     */


    public function contact(Request $request, \Swift_Mailer $mailer)
    {

        $user = $this->getUser();

        // Test pour savoir si l'utilisateur est connécté ou non 

        if ($user) {

            // si l'utilisateur est connécté, on récupère ses informations

            $username = $user->getEmail();
            $name = $user->getLastName();
            $firstname = $user->getFirstName();


            // Création formulaire

            $form = $this->createFormBuilder()
                ->add('name', TextType::class, $this->getConfiguration("Nom", "Entrer votre nom de famille"))
                ->add('firstname', TextType::class, $this->getConfiguration("Prénom", "Entrer votre prénom"))
                ->add('mail', EmailType::class, $this->getConfiguration("Mail", "Entrer votre mail"))
                ->add('message', TextareaType::class, $this->getConfiguration("Message", "Entrer votre message"))
                ->getForm();



            $form->handleRequest($request);

            // si le formulaire est validé, on gère l'envoi de mail

            if ($form->isSubmitted() && $form->isValid()) {
                $ourFormData = $form->getData();

                $message = (new \Swift_Message('Hello Mail'))
                    ->setFrom([$ourFormData['mail'] => $ourFormData['mail']])
                    ->setTo('martinpetit1998@gmail.com')
                    ->setBody(
                        $ourFormData['message'],
                        'text/plain'
                    );

                $mailer->send($message);

                    // Ajout d'un message permettant d'informer l'utilisateur que le message a bien été envoyé

                $this->addFlash(
                    'success',
                    "Votre message a bien été envoyé"
                );

                return $this->redirectToRoute('homepage');
            }

            return $this->render('Home/home.html.twig', [
                'form' => $form->createView(),
                'username' => $username,
                'name' => $name,
                'firstname' => $firstname


            ]);

            // s'il n'y aaucun utilisateurs connécté, on ne recupere pas d'informations

        } else {
            $form = $this->createFormBuilder()
                ->add('name', TextType::class, $this->getConfiguration("Nom", "Entrer votre nom de famille"))
                ->add('firstname', TextType::class, $this->getConfiguration("Prénom", "Entrer votre prénom"))
                ->add('mail', EmailType::class, $this->getConfiguration("Mail", "Entrer votre mail"))
                ->add('message', TextareaType::class, $this->getConfiguration("Message", "Entrer votre message"))
                ->getForm();



            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                $ourFormData = $form->getData();

                $message = (new \Swift_Message('Hello Mail'))
                    ->setFrom([$ourFormData['mail'] => $ourFormData['mail']])
                    ->setTo('martinpetit1998@gmail.com')
                    ->setBody(
                        $ourFormData['message'],
                        'text/plain'
                    );

                $mailer->send($message);

                $this->addFlash(
                    'success',
                    "Votre message a bien été envoyé"
                );

                return $this->redirectToRoute('homepage');
            }

            return $this->render('Home/home.html.twig', [
                'form' => $form->createView(),



            ]);
        }
    }
}
