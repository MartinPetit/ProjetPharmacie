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
     * @Route("/teleconsultation", name="rendezvous_prise")
     * @IsGranted("ROLE_USER")
     * Page de prise de rendez vous teleconsultation
     */
    public function Priserendezvous(Request $request, ObjectManager $manager)
    {
        $rendezvous = new Rendezvous();


        $form = $this->createForm(RendezvousType::class, $rendezvous);


        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user = $this->getUser();


            $time = $rendezvous->getDate();
            $test = clone ($time);
            $rendezvous->setEndDate($test->add(new DateInterval('PT60M')));


            $rendezvous->setClient($user);



            $rdv = $this->getDoctrine()
                ->getRepository(Rendezvous::class)
                ->findAll();

            // on stocke dans un tableau $notAvailableDays les dates de rendez vous déja prises

            $notAvailableDays = [];
            foreach ($rdv as $rendezVous) {
                $resultat = range(
                    $rendezVous->getDate()->getTimestamp(),
                    $rendezVous->getEndDate()->getTimestamp(),
                    1 * 60
                );
                $days = array_map(function ($dayTimestamp) {
                    return new \DateTime(date('d-m-Y H:i', $dayTimestamp));
                }, $resultat);
                $notAvailableDays = array_merge($notAvailableDays, $days);
            }

            // on stocke dans le tableau days les dates rentrées par l'utilisateur lors de l'envoi du formulaire

            $resultats = range(
                $rendezvous->getDate()->getTimestamp(),
                $rendezvous->getEndDate()->getTimestamp(),
                1 * 60
            );


            $days = array_map(function ($dayTimestamp) {
                return new \DateTime(date('d-m-Y H:i', $dayTimestamp));
            }, $resultats);

            // On compare les deux tableaux


            $dayss = array_map(function ($day) {
                return $day->format('d-m-Y H:i');
            }, $days);
            $notAvailable = array_map(function ($day) {
                return $day->format('d-m-Y H:i');
            }, $notAvailableDays);

            foreach ($dayss as $day) {

                if (array_search($day, $notAvailable) !== false) {
                    $this->addFlash(
                        'warning',
                        'dates indisponibles'
                    );
                    break;
                } else {
                    $manager->persist($rendezvous);
                    $manager->flush();
                    return $this->redirectToRoute('account_rendezvous');
                }
            }
        }



        // Recupération des prochains rendez vous

        $events = $this->getDoctrine()
            ->getRepository(Rendezvous::class)
            ->findMesRdv();



        // Suppression automatique Rendez vous qui ont une date antèrieure à la date du jour

        $remove = $this->getDoctrine()
            ->getRepository(Rendezvous::class)
            ->findRendezVous();

        foreach ($remove as $rdvRemove) {
            $manager->remove($rdvRemove);
            $manager->flush();
        }


        return $this->render('rendezvous/rdv.html.twig', [
            'form' => $form->createView(),
            'events' => $events

        ]);
    }





    /**
     * @Route("/MesRendezVous", name = "account_rendezvous")
     * Page "mes rendez vous"
     */

    public function showall(RendezvousRepository $repo)
    {

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
     * supprimer son rendez vous
     */

    public function delete(Rendezvous $rdv, ObjectManager $manager)
    {
        $manager->remove($rdv);
        $manager->flush();

        $this->addFlash(
            'success',
            "Le rendez vous du {$rdv->getDate()->format('d-m-Y à H:i')} a bien été supprimé"
        );



        return $this->redirectToRoute("account_rendezvous");
    }
}
