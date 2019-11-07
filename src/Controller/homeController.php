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

class homeController extends AbstractController {

	 /**
     * @Route("/", name="homepage")
     */

	public function home() {
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
    
    private function getConfiguration($label, $placeholder) {
        return  [
            'label' => $label, 
        'attr' => [
            'placeholder' => $placeholder
        ]
        ];
    }
    
    

	 /**
     * @Route("/", name="homepage")
     */


    public function test(Request $request, \Swift_Mailer $mailer) {

            $user = $this->getUser();

            if ($user) {

            $username = $user->getEmail();
            $name = $user->getLastName();
            $firstname = $user->getFirstName();

            


            $form = $this->createFormBuilder()
            ->add('name', TextType::class, $this->getConfiguration("Nom", "Entrer votre nom de famille"))
            ->add('firstname', TextType::class, $this->getConfiguration("Prénom", "Entrer votre prénom"))
            ->add('mail', EmailType::class, $this->getConfiguration("Mail", "Entrer votre mail"))
            ->add('message', TextareaType::class, $this->getConfiguration("Message", "Entrer votre message"))
            ->getForm()
            ;


    
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                $ourFormData = $form->getData();

                $message = (new \Swift_Message('Hello Mail'))
                    ->setFrom([$ourFormData['mail'] => $ourFormData['mail']] )
                    ->setTo('martinpetit1998@gmail.com')
                    ->setBody(
                        $ourFormData['message']
                    ,  'text/plain'
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
                'username' => $username,
                'name' => $name,
                'firstname' =>$firstname
            

            ]);

            } else {
                $form = $this->createFormBuilder()
            ->add('name', TextType::class, $this->getConfiguration("Nom", "Entrer votre nom de famille"))
            ->add('firstname', TextType::class, $this->getConfiguration("Prénom", "Entrer votre prénom"))
            ->add('mail', EmailType::class, $this->getConfiguration("Mail", "Entrer votre mail"))
            ->add('message', TextareaType::class, $this->getConfiguration("Message", "Entrer votre message"))
            ->getForm()
            ;


    
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                $ourFormData = $form->getData();

                $message = (new \Swift_Message('Hello Mail'))
                    ->setFrom([$ourFormData['mail'] => $ourFormData['mail']] )
                    ->setTo('martinpetit1998@gmail.com')
                    ->setBody(
                        $ourFormData['message']
                    ,  'text/plain'
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