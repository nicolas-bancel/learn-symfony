<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class IphoneController extends AbstractController
{

    private $products = [];

    public function __construct()
    {
        $this->products = [
            ['name' => 'iPhone X', 'slug' => 'iphone-x', 'description' => 'un iPhone de 2017', 'price' => 999],
            ['name' => 'iPhone XR', 'slug' => 'iphone-xr', 'description' => 'un iPhone de 2018', 'price' => 1099],
            ['name' => 'iPhone XS', 'slug' => 'iphone-xS', 'description' => 'un iPhone de 2018', 'price' => 1199]
        ];
    }

    /**
     * @Route("/product", name="product")
     */
    public function product()
    {
        return $this->render('product/product.html.twig', [
            'products' => $this->products
        ]);
    }

    /**
     * @Route("/product/random", name="random")
     */
    public function random()
    {
        $rand = array_rand($this->products, 1);
        //il fallait mettre $this->product[], ne pas oublier le crochet pour indiquer quel index mettre
        $rand = $this->products[array_rand($this->products, 1)];

        dump($this->products[array_rand($this->products, 1)]);

        return $this->render('product/slug.html.twig', [
            'products' => $rand
        ]);
    }

    /**
     * @Route("/product/create", name="product_create")
     */
    public function create()
    {
        return $this->render('product/create.html.twig');
    }

    /**
     * @Route("/product/{slug}", name="slug")
     */
    public function slug($slug)
    {
        dump($slug);
        // dump($this->products);

        foreach ($this->products as $product)
        {
            // dump($product);
            if ($slug == $product['slug'])
            {
                dump($product);
                //si un produit correspond, on retourne le template et on arrete le code
                return $this->render('product/slug.html.twig', [
                    'product' => $product,
                    'slug' => $slug
                ]);
            }
        }

        // apres avoir parcouru le tableau, si rien ne correspond on affiche une 404
        throw $this->createNotFoundException('le produit n\'existe pas.');
    }

    /**
     * @Route("/product/order/{slug}", name="order")
     */
    public function order($slug)
    {
        $this->addFlash('success', 'Nous avons bien pris en compte votre commande de'.$slug );

        return $this->redirectToRoute('product');
    }

}