<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class homeController extends AbstractController {

	 /**
     * @Route("/", name="homepage")
     */

	public function home() {
    	return $this->render('Home/home.html.twig', [
    		'title' => "Pharmacie Pasteur",
    	]);
    }






}