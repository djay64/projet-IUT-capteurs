<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

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
    public function gestionCapteur(Request $request)
    {
        $defaultData = ['message' => 'Type your message here'];
        
        $form = $this->createFormBuilder($defaultData)
            ->add('nomDuCapteur')
            ->getForm();
    
        $form->handleRequest($request);
    
        if ($form->isSubmitted() && $form->isValid()) {
            // data is an array with "name", "email", and "message" keys
            $data = $form->getData();
        }
    
        $valueZoneAction = "medium";
        return $this->render('capteurs_pollution/gestion-capteurs.html.twig', ['form'=>$form->createView(), "valueZoneAction"=>$valueZoneAction]);
 
    }
}