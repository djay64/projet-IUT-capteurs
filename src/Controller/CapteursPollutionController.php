<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response; 

use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

use App\Entity\Capteur;
use App\Entity\Releve;


use Doctrine\Common\Persistence\ObjectManager;



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

 
        $niveauActuelParticulePm10 = $relevesPm10[date("H")][1];
        $niveauActuelParticulePm10 = substr($niveauActuelParticulePm10, 0, 4);
   

        if ( $niveauActuelParticulePm10 >= 0 && $niveauActuelParticulePm10 < 28 ) {
            $couleurParticulePm10 = "bg-success";
        }  
        if ( $niveauActuelParticulePm10 >= 28 && $niveauActuelParticulePm10 < 50 ) {
            $couleurParticulePm10 = "bg-warning";
        }  
        if ( $niveauActuelParticulePm10 >= 50 ) {
            $couleurParticulePm10 = "bg-danger";
        }  

 
        $niveauActuelParticulePm25 = $relevesPm25[date("H")][1];
        $niveauActuelParticulePm25 = substr($niveauActuelParticulePm25, 0, 4);

        if ( $niveauActuelParticulePm25 >= 0 && $niveauActuelParticulePm25 < 28 ) {
            $couleurParticulePm25 = "bg-success";
        } 
        if ( $niveauActuelParticulePm25 >= 28 && $niveauActuelParticulePm25 < 50 ) {
            $couleurParticulePm25 = "bg-warning";
        }  
        if ( $niveauActuelParticulePm25 >= 50 ) {
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
        $dateDuJourDate = date("m-d-Y");

        $formulaireFiltres = $this->createFormBuilder($saisieFiltres)
        ->add('titre', TextType::class)
        ->add('dateDebut', DateType::class, ['label' => 'Début', 'widget' => 'single_text'])
        ->add('dateFin', DateType::class, ['label' => 'Fin', 'widget' => 'single_text'])
        ->add('typeParticule', ChoiceType::class, ['label' => 'Particules', 'choices' => ['PM 2.5' => 'pm25', 'PM 10' => 'pm10'], 'expanded' => true, 'multiple' => true, 'empty_data' => ['pm25', 'pm10']])
        ->add('capteurs', EntityType::class, ['class' => Capteur::class, 'choice_label' => 'nom', 'multiple' => true, 'expanded' => false])
        ->add('typeGraphique', ChoiceType::class, ['label' => 'Type de graphique', 'choices' => ['Barre' => 'bar', 'Ligne' => 'line', 'Radar' => 'radar', 'Secteur' => 'pie', 'Donut' => 'doughnut', 'Polaire' => 'polarArea'], 'expanded' => true, 'multiple' => false])
        ->add('Modifier', SubmitType::class)
        ->add('Exporter', SubmitType::class)
        ->getForm();
        
        $formulaireFiltres->handleRequest($requete);
        
        if($formulaireFiltres->isSubmitted() && $formulaireFiltres->isValid()){       
            $filtres = $formulaireFiltres->getData();

            $capteurs = [];

            foreach ($filtres['capteurs'] as $capteur) {
                array_push($capteurs, $capteur->getNom());
            }


            if(sizeof($filtres['typeParticule']) > 1){
                $relevesPm25 = $repositoryReleve->findByPm25($filtres['dateDebut'], $filtres['dateFin'], $capteurs);
                $relevesPm10 = $repositoryReleve->findByPm10($filtres['dateDebut'], $filtres['dateFin'], $capteurs);

                return $this->render('capteurs_pollution/graphique.html.twig', ['selectionFiltres' => $formulaireFiltres->createView(), 'relevesPm10' => $relevesPm10, 'relevesPm25' => $relevesPm25, 'typeGraphique' => $filtres['typeGraphique'],'dateDebut' => $filtres['dateDebut']->format('md') ,'dateFin' => $filtres['dateFin']->format('md'),'dateDebutDate' => $filtres['dateDebut']->format('m-d-Y') ,'dateFinDate' => $filtres['dateFin']->format('m-d-Y'),'titre' => $filtres['titre'] ]);
            }elseif ($filtres['typeParticule'][0] == 'pm25') {
                $relevesPm25 = $repositoryReleve->findByPm25($filtres['dateDebut'], $filtres['dateFin'], $capteurs);

                return $this->render('capteurs_pollution/graphique.html.twig', ['selectionFiltres' => $formulaireFiltres->createView(), 'relevesPm10' => null, 'relevesPm25' => $relevesPm25, 'typeGraphique' => $filtres['typeGraphique'],'dateDebut' => $filtres['dateDebut']->format('md') ,'dateFin' => $filtres['dateFin']->format('md'),'dateDebutDate' => $filtres['dateDebut']->format('m-d-Y') ,'dateFinDate' => $filtres['dateFin']->format('m-d-Y'),'titre' => $filtres['titre']  ]);
            }else{
                $relevesPm10 = $repositoryReleve->findByPm10($filtres['dateDebut'], $filtres['dateFin'], $capteurs);

                return $this->render('capteurs_pollution/graphique.html.twig', ['selectionFiltres' => $formulaireFiltres->createView(), 'relevesPm10' => $relevesPm10, 'relevesPm25' => null, 'typeGraphique' => $filtres['typeGraphique'],'dateDebut' => $filtres['dateDebut']->format('md'),'dateFin' => $filtres['dateFin']->format('md'),'dateDebutDate' => $filtres['dateDebut']->format('m-d-Y') ,'dateFinDate' => $filtres['dateFin']->format('m-d-Y'),'titre' => $filtres['titre'] ]);
            }
            
        }   
        
        return $this->render('capteurs_pollution/graphique.html.twig', ['selectionFiltres' => $formulaireFiltres->createView(), 'relevesPm10' => $relevesPm10, 'relevesPm25' => $relevesPm25, 'typeGraphique' => 'line','dateDebut' => $dateDuJour,'dateFin' =>$dateDuJour,'dateDebutDate' =>$dateDuJourDate,'dateFinDate' =>$dateDuJourDate,'titre' => 'Titre du Graphique'  ]);
    }






    /**
     * @Route("/gestionCapteurs/", name="capteurs_pollution_gestionCapteur")
     */
    public function gestionCapteur(Request $request)
    {
        $repositoryCapteur = $this->getDoctrine()->getRepository(Capteur::class);
        $listeCapteur = $repositoryCapteur->findAll();

        $defaultData = ['message' => 'Type your message here'];
            $form = $this->createFormBuilder($defaultData)
            ->add('nomDuCapteur')
            ->getForm();
    
            $form->handleRequest($request);
    
            if ($form->isSubmitted()) {
                $data = $form->getData();

                $repositoryCapteur = $this->getDoctrine()->getRepository(Capteur::class);
                $resultatFinByNom = $repositoryCapteur->findByNom($data["nomDuCapteur"]);  
                
                
                if (sizeof($resultatFinByNom) == 0){
                    
                    return $this->redirectToRoute('ajouterCapteur', ['nomCapteur' => $data["nomDuCapteur"]]);
   
                } else {
                    
                    return $this->redirectToRoute('ModifierSupprimerCapteur', ['nomCapteur' => $data["nomDuCapteur"]]);
                  
                }
                
            }
            return $this->render('capteurs_pollution/gestion-capteurs.html.twig', ['form'=>$form->createView(), 'capteurs'=> $listeCapteur]);

    }



    /**
     * @Route("/gestionCapteurs/modifierOuSupprimer/{nomCapteur}", name="ModifierSupprimerCapteur")
     */
    public function modifierOuSupprimer(Request $request, Capteur $nomCapteur)
    {
        $repositoryCapteur = $this->getDoctrine()->getRepository(Capteur::class);
        $listeCapteur = $repositoryCapteur->findAll();
        $resultatFinByNom = $repositoryCapteur->findByNom($nomCapteur);  
        $capteurCible = $resultatFinByNom[0];


        $defaultData = ['message' => 'Type your message here']; 
        $form = $this->createFormBuilder($nomCapteur)
        ->add('latitude')
        ->add('longitude')
        ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted()) {
        }

            
        return $this->render('capteurs_pollution/forms/modifyOrDelet.html.twig', ['form'=>$form->createView(), 'capteur' => $capteurCible, 'capteurs'=> $listeCapteur ]);

    }


    /**
     * @Route("/gestionCapteurs/ajouter/{nomCapteur}", name="ajouterCapteur")
     */
    public function ajouterCapteur(Request $request, string $nomCapteur)
    {
        $repositoryCapteur = $this->getDoctrine()->getRepository(Capteur::class);
        $listeCapteur = $repositoryCapteur->findAll();
 

        $nouveauCapteur = new Capteur(); 

        $formCapteur = $this->createFormBuilder($nouveauCapteur)
        ->add('nom',  TextType::class, ['data' => $nomCapteur, 'empty_data' => $nomCapteur])
        ->add('latitude', TextType::class)
        ->add('longitude', TextType::class)
        
        ->getForm();

        $formCapteur->handleRequest($request);
 

        if ($formCapteur->isSubmitted()) {
 
                $this->getDoctrine()->getManager()->persist($nouveauCapteur);
                $this->getDoctrine()->getManager()->flush(); 
                return $this->redirectToRoute('capteurs_pollution_gestionCapteur');
 
            
        }

            
        return $this->render('capteurs_pollution/forms/addOrModify.html.twig', ['formCapteur'=>$formCapteur->createView(),'typeAction'=>"ajouter", 'capteurs'=> $listeCapteur]);

    }
    


    /**
     * @Route("/gestionCapteurs/modifier/{nomCapteur}", name="modifierCapteur")
     */
    public function modifierCapteur(Request $request, Capteur $nomCapteur)
    {
        $repositoryCapteur = $this->getDoctrine()->getRepository(Capteur::class);
        $listeCapteur = $repositoryCapteur->findAll();
        $resultatFinByNom = $repositoryCapteur->findByNom($nomCapteur);  
        $capteurCible = $resultatFinByNom[0];
 
        $formCapteur = $this->createFormBuilder($capteurCible)
        ->add('nom', TextType::class, ['disabled' => true])
        ->add('latitude', TextType::class)
        ->add('longitude', TextType::class) 
        ->getForm();
    
        $formCapteur->handleRequest($request);

        if ($formCapteur->isSubmitted()) {
 
                $this->getDoctrine()->getManager()->persist($capteurCible);
                $this->getDoctrine()->getManager()->flush(); 
                return $this->redirectToRoute('capteurs_pollution_gestionCapteur');
 
             
        }
            return $this->render('capteurs_pollution/forms/addOrModify.html.twig', ['formCapteur'=>$formCapteur->createView(),'typeAction'=>'modifier', 'capteurs'=> $listeCapteur, 'capteur' => $capteurCible]);
    }
    
    /**
     * @Route("/gestionCapteurs/supprimer/{nomCapteur}", name="supprimerrCapteur")
     */
    public function supprimerCapteur(Request $request, Capteur $nomCapteur)
    {
        $repositoryCapteur = $this->getDoctrine()->getRepository(Capteur::class);

        $resultatFinByNom = $repositoryCapteur->findByNom($nomCapteur);  
        $capteurCible = $resultatFinByNom[0];
 
        $this->getDoctrine()->getManager()->remove($capteurCible);
        $this->getDoctrine()->getManager()->flush(); 
        return $this->redirectToRoute('capteurs_pollution_gestionCapteur');
     
    }


}