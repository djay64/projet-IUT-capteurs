<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use App\Entity\Capteur;
use App\Entity\Releve;

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
        $repositoryReleve = $this->getDoctrine()->getRepository(Releve::class);
        $relevesPm10 = $repositoryReleve->findByPm10MoyJour();
        $relevesPm25 = $repositoryReleve->findByPm25MoyJour();

        $globalArrayNiveauPm10 = $repositoryReleve->findByPm10Heure(); 
        $niveauActuelParticulePm10 = $globalArrayNiveauPm10[0][1];
        $niveauActuelParticulePm10 = substr($niveauActuelParticulePm10, 0, 4);
        


        if ( $niveauActuelParticulePm10 >= 0 && $niveauActuelParticulePm10 < 10 ) {
            $couleurParticulePm10 = "bg-success";
        }  
        if ( $niveauActuelParticulePm10 >= 10 && $niveauActuelParticulePm10 < 20 ) {
            $couleurParticulePm10 = "bg-warning";
        }  
        if ( $niveauActuelParticulePm10 >= 20 ) {
            $couleurParticulePm10 = "bg-danger";
        }  



        
        $globalArrayNiveauPm25 = $repositoryReleve->findByPm25Heure(); 
        $niveauActuelParticulePm25 = $globalArrayNiveauPm25[0][1];
        $niveauActuelParticulePm25 = substr($niveauActuelParticulePm25, 0, 4);

        if ( $niveauActuelParticulePm25 >= 0 && $niveauActuelParticulePm25 < 10 ) {
            $couleurParticulePm25 = "bg-success";
        } 
        if ( $niveauActuelParticulePm25 >= 10 && $niveauActuelParticulePm25 < 20 ) {
            $couleurParticulePm25 = "bg-warning";
        }  
        if ( $niveauActuelParticulePm25 >= 20 ) {
            $couleurParticulePm25 = "bg-danger";
        }   







        return $this->render('capteurs_pollution/accueil.html.twig', ['relevesPm10' => $relevesPm10, 'relevesPm25' => $relevesPm25, 'particulePm10' => $niveauActuelParticulePm10,  'particulePm25' => $niveauActuelParticulePm25, 'couleurPm10' => $couleurParticulePm10, 'couleurPm25' => $couleurParticulePm25]);
    }
    
    /**
     * @Route("/graphique", name="capteurs_pollution_graphique")
     */
    public function genererGraphique(Request $requete){
        $repositoryReleve = $this->getDoctrine()->getRepository(Releve::class);
        $saisieFiltres = ['titre' => 'Titre du graphique'];
        $relevesPm10 = $repositoryReleve->findByPm10MoyJour();
        $relevesPm25 = $repositoryReleve->findByPm25MoyJour();
        $dateDuJour = date("md"); 

        $formulaireFiltres = $this->createFormBuilder($saisieFiltres)
        ->add('titre', TextType::class)
        ->add('dateDebut', DateType::class, ['label' => 'Début', 'widget' => 'single_text'])
        ->add('dateFin', DateType::class, ['label' => 'Fin', 'widget' => 'single_text'])
        ->add('typeParticule', ChoiceType::class, ['label' => 'Particules', 'choices' => ['PM 2.5' => 'pm25', 'PM 10' => 'pm10'], 'expanded' => true, 'multiple' => true, 'empty_data' => ['pm25', 'pm10']])
        ->add('capteurs', ChoiceType::class, ['label' => 'Capteurs', 'choices' => ['Capteur 1' => 'Capteur1', 'Capteur 2' => 'Capteur2', 'Capteur 3' => 'Capteur3'], 'expanded' => false, 'multiple' => true])
        ->add('typeGraphique', ChoiceType::class, ['label' => 'Type de graphique', 'choices' => ['Barre' => 'bar', 'Ligne' => 'line', 'Radar' => 'radar', 'Secteur' => 'pie', 'Donut' => 'doughnut', 'Polaire' => 'polarArea'], 'expanded' => true, 'multiple' => false])
        ->add('Modifier', SubmitType::class)
        ->add('Exporter', SubmitType::class)
        ->getForm();
        
        $formulaireFiltres->handleRequest($requete);
        
        if($formulaireFiltres->isSubmitted() && $formulaireFiltres->isValid()){       
            $filtres = $formulaireFiltres->getData();

            if(sizeof($filtres['typeParticule']) > 1){
                $relevesPm25 = $repositoryReleve->findByPm25($filtres['dateDebut'], $filtres['dateFin'], $filtres['capteurs']);
                $relevesPm10 = $repositoryReleve->findByPm10($filtres['dateDebut'], $filtres['dateFin'], $filtres['capteurs']);
                return $this->render('capteurs_pollution/graphique.html.twig', ['selectionFiltres' => $formulaireFiltres->createView(), 'relevesPm10' => $relevesPm10, 'relevesPm25' => $relevesPm25, 'typeGraphique' => $filtres['typeGraphique'],'dateDebut' => $filtres['dateDebut']->format('md') ,'dateFin' => $filtres['dateFin']->format('md') ]);
            }elseif ($filtres['typeParticule'][0] == 'pm25') {
                $relevesPm25 = $repositoryReleve->findByPm25($filtres['dateDebut'], $filtres['dateFin'], $filtres['capteurs']);
                return $this->render('capteurs_pollution/graphique.html.twig', ['selectionFiltres' => $formulaireFiltres->createView(), 'relevesPm10' => null, 'relevesPm25' => $relevesPm25, 'typeGraphique' => $filtres['typeGraphique'],'dateDebut' => $filtres['dateDebut']->format('md') ,'dateFin' => $filtres['dateFin']->format('md') ]);
            }else{
                $relevesPm10 = $repositoryReleve->findByPm10($filtres['dateDebut'], $filtres['dateFin'], $filtres['capteurs']);
                return $this->render('capteurs_pollution/graphique.html.twig', ['selectionFiltres' => $formulaireFiltres->createView(), 'relevesPm10' => $relevesPm10, 'relevesPm25' => null, 'typeGraphique' => $filtres['typeGraphique'],'dateDebut' => $filtres['dateDebut']->format('md'),'dateFin' => $filtres['dateFin']->format('md')]);
            }
            
        }   
        
        return $this->render('capteurs_pollution/graphique.html.twig', ['selectionFiltres' => $formulaireFiltres->createView(), 'relevesPm10' => $relevesPm10, 'relevesPm25' => $relevesPm25, 'typeGraphique' => 'line','dateDebut' => $dateDuJour,'dateFin' =>$dateDuJour]);
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