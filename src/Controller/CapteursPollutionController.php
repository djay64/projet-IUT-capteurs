<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class CapteursPollutionController extends AbstractController
{
    /**
     * @Route("/capteurs/pollution", name="capteurs_pollution")
     */
    public function index()
    {
        return $this->render('capteurs_pollution/index.html.twig', [
            'controller_name' => 'CapteursPollutionController',
        ]);
    }
    /**
     * @Route("/", name="capteurs_pollution_accueil")
     */
    public function accueil()
    {
        return $this->render('capteurs_pollution/accueil.html.twig');
    }
    
    /**
     * @Route("/graphique", name="capteurs_pollution_graphique")
     */
    public function graphique()
    {
        return $this->render('capteurs_pollution/graphique.html.twig');
    }


    /**
     * @Route("/gestionCapteurs", name="capteurs_pollution_gestionCapteur")
     */
    public function gestionCapteur()
    {
        return $this->render('capteurs_pollution/gestion-capteurs.html.twig');
    }
}