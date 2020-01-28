<?php

namespace App\Controller;

use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

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

    /**
     * on va créer 2 nouvelles routes:
     * /voir-session : afficher le contenu de la clé 'name' dans la session
     *                 n'affiche rien lors de la premiere visite sur le site
     * /mettre-en-session/{name}: mettre en session la valeur passée dans l'url
     */

    /**
     * @Route("/voir-session", name="show_session")
     */
    public function showSession(SessionInterface $session)
    {
        dump($session->get('name'));

        return $this->render('demo/show_session.html.twig');
    }

    /**
     * @Route("/mettre-en-session/{name}", name="put_session")
     */
    public function putSession($name, SessionInterface $session)
    {
        //je mets $name en session
        $session->set('name', $name);

        $this->addFlash('success', 'message de succès');

        return $this->redirectToRoute('show_session');
    }

    /**
     * @Route("/protected.pdf", name="cv")
     */
    public function downloadCV()
    {
        $autorized = (bool) rand(0,1);

        if($autorized) {
            throw $this->createNotFoundException('vous ne pouvez pas');
        }

        return $this->file(
            '../cv.pdf',
            'new_file.pdf',
            ResponseHeaderBag::DISPOSITION_INLINE
        );
    }
}
