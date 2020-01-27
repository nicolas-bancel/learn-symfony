<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
// use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class WelcomeController extends AbstractController
{

    /**
     * @Route("/hello", name="hello")
     */
    public function hello()
    {
        $name = 'Nicolas';

        return $this->render('welcome/hello.html.twig', [
            'name' => $name,
        ]);

        // return new Response(
        //     '<body>Salut '.$name.'</body>'
        // );

    }

    /**
     * @route(
     * "/hello/{slug}",
     * name="hello",
     * requirements={"slug"="\w{3,8}"}
     * )
     */
    public function show($slug = 'Nicola')
    {
        $name = $slug;

        return $this->render('welcome/hello.html.twig', [
            'name' => $name,
        ]);

    }

}