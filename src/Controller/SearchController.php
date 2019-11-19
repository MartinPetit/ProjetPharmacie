<?php

namespace App\Controller;

use App\Repository\ProductRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


/**  
*@route("/search", name = "search.") 
*/

class SearchController extends AbstractController
{
 

    /**
     * @Route("/", methods ={"GET"})
     */

    public function searchbar() {
        $form = $this->createFormBuilder()
            ->setAction($this->generateUrl('search.handleSearch'))
            ->add('query', TextType::class)
            ->add('search', SubmitType::class, [
                'attr' => [
                    'class' => 'btn btn-primary'
                ]
            ])

            ->getForm();

            return $this->render('search/searchBar.html.twig', [
                'form' => $form->createView(),
            ]);

    }


    /**
     *@Route("/handleSearch", name="handleSearch")
     */

    public function handlesearch(Request $request, ProductRepository $productrepo) {
        $querry = $request->request->get('form')['query'];
        if($querry) {
            $products = $productrepo->findByName($querry);
        }

        dump($products);

        return $this->render('product/index.html.twig', [
            'products' => $products
        ]);
        
    }
}
