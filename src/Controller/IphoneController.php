<?php

namespace App\Controller;

use App\Model\Product;
use App\Model\Contact;
use App\Form\ProductType;
use App\Form\ContactType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;

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
    public function create(Request $request)
    {
        $product = new Product();
        dump($product);

        $form = $this->createForm(ProductType::class, $product);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
            dump($form->getData());
            dump($product);

            //redirige, bdd, emailing...
        }

        return $this->render('product/create.html.twig',[
            'form' =>$form->createView(),
        ]);
    }



    /**
     * @Route("/contact", name="contact")
     */
    public function contact(Request $request, MailerInterface $mailer)
    {
        $contact = new Contact();

        $form = $this->createForm(ContactType::class, $contact);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
            dump($form->getData());
            dump($contact);

            $this->addFlash('success', 'Nous avons bien pris en compte votre message.' );

//            return $this->redirectToRoute('product');

            //redirige, bdd, emailing...
            $email = (new Email())
                ->from('hello@example.com')
                ->to('bancelnicolas@gmail.com')
                ->subject($contact->getName().'a fait une demande.')
                ->text('Sending emails is fun again!')
                ->html('<p>email: '.$contact->getMail().'</p>');

            $mailer->send($email);
        }

        return $this->render('product/contact.html.twig',[
            'form' =>$form->createView(),
        ]);
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