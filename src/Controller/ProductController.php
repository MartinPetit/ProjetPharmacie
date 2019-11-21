<?php

namespace App\Controller;

use App\Repository\CategoryRepository;
use App\Repository\ProductRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ProductController extends AbstractController
{
    /**
     * @Route("/products", name="index_product")
     * Permet d'afficher tous les produits 
     */
    public function index(ProductRepository $repo, CategoryRepository $repoc)
    {
        $products = $repo->findAll();

        $form = $this->createFormBuilder()
            ->setAction($this->generateUrl('handleSearch'))
            ->add('nom', TextType::class, [
                'label' => false,
                'attr' => [
                    'placeholder' => 'Rechercher un  produit'
                ]
            ])
            

            ->getForm();

        $categories = $repoc->findAll();
        

        return $this->render('product/index.html.twig', [
            'form' => $form->createView(),
            'products' => $products,
            'categories' => $categories
        ]);
    }

    /**
     *@Route("/Search", name="handleSearch")
     */

    public function handlesearch(Request $request, ProductRepository $productrepo) {
        $querry = $request->request->get('form')['nom'];
        if($querry) {
            $products = $productrepo->findByName($querry);
        }

        dump($products);

        return $this->render('search/index.html.twig', [
            'products' => $products,
            
        ]);
        
    }



    /**
     * @Route("/products/{slug}", name = "show_products")
     * Affiche les détails du produits séléctionné
     * @return Response
     */

    public function show($slug, ProductRepository $repo)
    {
        $product = $repo->findOneBySlug($slug);


        return $this->render('product/show.html.twig', [
            'product' => $product
        ]);
    }

    /**
     * @Route("/products/categories/{id}", name = "show_catego")
     */

    public function showC($id, CategoryRepository $repocc)
    {
        $catego = $repocc->findOneById($id);
        

        return $this->render('category/show.html.twig', [
            'catego' => $catego
        ]);
    }
}
