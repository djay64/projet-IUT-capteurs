<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

use Symfony\Component\Form\Extension\Core\Type\TextType;

//use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Twig\Environment;


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
     * @Route("/gestionCapteurs/{typeAction}/{nomCapteur}", name="capteurs_pollution_gestionCapteur")
     */
    public function gestionCapteur(Request $request, Environment $twig, $nomCapteur = "noVariable", $typeAction ="basicAction")
    {
        if ($typeAction == "basicAction") {
            $defaultData = ['message' => 'Type your message here'];
            $form = $this->createFormBuilder($defaultData)
            ->add('nomDuCapteur')
            ->getForm();
    
            $form->handleRequest($request);
    
            if ($form->isSubmitted()) {
                $data = $form->getData();


                if (false) {
                    
                    return $this->redirectToRoute('capteurs_pollution_gestionCapteur', ['typeAction' =>'ajouter' ,'nomCapteur' => $data["nomDuCapteur"]]);

                } else {
                    
                    return $this->redirectToRoute('capteurs_pollution_gestionCapteur', ['typeAction' =>'modifierOuSupprimer' ,'nomCapteur' => $data["nomDuCapteur"]]);
                  
                }
                
            }
            return $this->render('capteurs_pollution/gestion-capteurs.html.twig', ['form'=>$form->createView(), 'nomCapteur'=>'Un capteur']);
        }
    



        if ($typeAction == "modifierOuSupprimer") {
            $nomDuCapteur = $nomCapteur;
            $latitude = "latitude";
            $longitude = "longitude";
            return $this->render('capteurs_pollution/forms/modifyOrDelet.html.twig', ['nomDuCapteur'=>$nomCapteur,'latitude'=>$latitude, 'longitude'=>$longitude]);
        }



        if ($typeAction == "ajouter") {
            $nomDuCapteur = $nomCapteur;
            $latitude = "latitude";
            $longitude = 1;
            $typeAction = "Ajouter";



            $defaultData = ['message' => 'Type your message here'];
            $form = $this->createFormBuilder($defaultData)
            ->add('latitude')
            ->add('longitude')
            ->getForm();
    
            $form->handleRequest($request);
    
            if ($form->isSubmitted()) {
            }

                
            return $this->render('capteurs_pollution/forms/addOrModify.html.twig', ['form'=>$form->createView(), 'nomDuCapteur'=>$nomDuCapteur, 'typeAction'=>$typeAction ,'latitude'=>$latitude, 'longitude'=>$longitude]);
        }


 



        if ($typeAction == "modifier") {
            $nomDuCapteur = $nomCapteur;
            $latitude = "latitude";
            $longitude = "longitude";
            $typeAction = "Modifier";

            $defaultData = ['message' => 'Type your message here'];
            $form = $this->createFormBuilder($defaultData)
            ->add('latitude', TextType::class, ['data' => $latitude])
            ->add('longitude', TextType::class, ['data' => $longitude])
            ->getForm();
    
            $form->handleRequest($request);
    
            if ($form->isSubmitted()) {
            }

            return $this->render('capteurs_pollution/forms/addOrModify.html.twig', ['form'=>$form->createView(), 'nomDuCapteur'=>$nomDuCapteur, 'typeAction'=>$typeAction, 'latitude'=>$latitude, 'longitude'=>$longitude]);
        }





        if ($typeAction == "supprimer") {
            $nomDuCapteur = $nomCapteur;
                
        }

    }




}