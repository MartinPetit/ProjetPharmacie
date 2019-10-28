<?php

namespace App\Controller;

use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class ProductController extends AbstractController
{
    /**
     * @Route("/products", name="index_product")
     */
    public function index(ProductRepository $repo)
    {
        $products = $repo->findAll();
        return $this->render('product/index.html.twig', [
            'products' => $products
        ]);
    }

    /**
     * @Route("/products/{slug}", name = "show_products")
     * @return Response
     */

    public function show($slug, ProductRepository $repo) {
        $product = $repo->findOneBySlug($slug);

        return $this->render('product/show.html.twig', [
            'product' => $product
        ]);

    }


}
