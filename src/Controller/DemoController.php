<?php

namespace App\Controller;

use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;


class DemoController extends AbstractController
{
    /**
     * @Route("/demo", name="demo")
     */
    public function index(Request $request)
    {
        //recupérer $_GET['A']
        dump($request->query->get('A'));

        //récuperer IP utilisateur
        dump($request->server->get('REMOTE_ADDR'));

        //renvoie une objet request


        return $this->render('demo/index.html.twig', [
            'controller_name' => 'DemoController',
        ]);
    }

    /**
     * @route("/toto", name="toto")
     * 
     * systeme de redirection
     */
    public function toto()
    {
        return $this->redirectToRoute('demo');
    }

    /**
     * @route("/event/{slug}")
     */
    public function showEvent(Request $request, $slug, LoggerInterface $logger)
    {
        $events = ['a', 'b', 'c'];

        if (!in_array($slug, $events)) {
            //si le slug n'est pas dans le tableau
            throw $this->createNotFoundException('Cet événement n\'est pas dispo');
        }

        $ip = $request->server->get('REMOTE_ADDR');
        $logger->info($ip.' a vu l\'evenement '.$slug);

        return new Response('<body>'.$slug.'</body>');  
    }
}
