<?php

namespace App\Controller;

use App\Entity\Prescription;
use App\Form\PrescriptionType;
use App\Repository\PrescriptionRepository;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class PrescriptionController extends AbstractController
{
    /**
     * @Route("/prescription", name="prescription")
     * @IsGranted("ROLE_USER")
     */
    public function new(Request $request, ObjectManager $manager, \Swift_Mailer $mailer)
    {
        $users = $this->getUser();

        $username = $users->getEmail();


        $prescription = new Prescription();

        $prescription->setUsers($users);
        $form = $this->createForm(PrescriptionType::class, $prescription);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $test = $form->getData();
            $mail = $test->getMail();
            $message = $test->getMessage();



            /** @var UploadedFile $brochureFile */
            $brochureFile = $form['brochureFilename']->getData();

            if ($brochureFile) {
                $originalFilename = pathinfo($brochureFile->getClientOriginalName(), PATHINFO_FILENAME);

                $safeFilename = transliterator_transliterate('Any-Latin; Latin-ASCII; [^A-Za-z0-9_] remove; Lower()', $originalFilename);
                $newFilename = $safeFilename . '-' . uniqid() . '.' . $brochureFile->guessExtension();

                try {
                    $brochureFile->move(
                        $this->getParameter('upload_directory'),
                        $newFilename
                    );
                } catch (FileException $e) { }


                $prescription->setBrochureFilename($newFilename);
            }

            $dossier = $this->getParameter("upload_directory");
            $fileend = $dossier . '/' . $newFilename;

            $message = (new \Swift_Message(''))
                ->setFrom($mail)
                ->setTo('martinpetit1998@gmail.com')
                ->attach(\Swift_Attachment::fromPath($fileend))
                ->setBody(
                    $message,
                    'text/plain'
                );

            $mailer->send($message);


            $manager->persist($prescription);
            $manager->flush();

            $this->addFlash(
                'success',
                "Votre ordonnance a bien été envoyé"
            );


            return $this->redirectToRoute('homepage');
        }

        return $this->render('prescription/index.html.twig', [
            'form' => $form->createView(),
            'username' => $username
        ]);
    }



    /**
     * @Route("/Prescription", name = "account_prescription")
     * @IsGranted("ROLE_USER")
     */

    public function show(PrescriptionRepository $repo)
    {

        $users = $this->getUser();

        $email = $users->getEmail();



        $prescriptions = $repo->findByMail($email);
        return $this->render('prescription/prescriptions.html.twig', [
            'prescriptions' => $prescriptions
        ]);
    }
}
