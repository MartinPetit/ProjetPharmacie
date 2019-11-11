<?php

namespace App\Controller;

use DateInterval;
use App\Entity\Rendezvous;
use App\Form\RendezvousType;
use App\Repository\RendezvousRepository;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class RendezvousController extends AbstractController
{
    /**
     * @Route("/ordonnance", name="rendezvous_prise")
     * @IsGranted("ROLE_USER")
     */
    public function Priserendezvous(Request $request, ObjectManager $manager)
    {
        $rendezvous = new Rendezvous();
        $form = $this->createForm(RendezvousType::class, $rendezvous);


        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user = $this->getUser();
            
            $time = $rendezvous->getDate();
            $test = clone($time);
            $rendezvous->setEndDate($test->add(new DateInterval('PT30M')));
            

            $rendezvous->setClient($user);

           if (!$rendezvous->isBookableDates()) {
               $this->addFlash(
                   'warning',
                   'dates indisponibles'
               );
           } else {
            $manager->persist($rendezvous);
            $manager->flush();

            return $this->redirectToRoute('account_rendezvous');
           }


           
        }

        return $this->render('rendezvous/rdv.html.twig', [
            'form' => $form->createView()
        ]);
    }

   

     /**
     * @Route("/MesRendezVous", name = "account_rendezvous")
     * 
     */

    public function showall(RendezvousRepository $repo) {

        $users = $this->getUser();

        $id = $users->getId();
    
        

        $rendezvous = $repo->findByClient($id);
        return $this->render('rendezvous/Mesrendezvous.html.twig', [
            'rendezvous' => $rendezvous
        ]);

    }


    /**
     * @Route("/MesRendezVous/{id}/delete", name = "rdv_delete")
     * @Security("is_granted('ROLE_USER') and user == rdv.getClient()", message = "vous n'avez pas les droits")
     * 
     */

    public function delete(Rendezvous $rdv, ObjectManager $manager) {
        $manager->remove($rdv);
        $manager->flush();

        $this->addFlash(
            'success',  
            "Le rendez vous du {$rdv->getDate()->format('d-m-Y à H:i:s')} a bien été supprimé");
        


        return $this->redirectToRoute("account_rendezvous");


    } 

    
}
