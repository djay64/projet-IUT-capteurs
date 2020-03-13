<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use App\Entity\Capteur;
use App\Entity\Releve;

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
        return $this->render('capteurs_pollution/accueil.html.twig', ['relevesPm10' => $relevesPm10, 'relevesPm25' => $relevesPm25]);

    }

    /**
     * @Route("/genererGraphique", name="capteurs_pollution_genererGraphique")
     */
    public function genererGraphique(Request $requete){
        $repositoryReleve = $this->getDoctrine()->getRepository(Releve::class);
        $saisieFiltres = ['titre' => 'Titre du graphique'];
        $relevesPm10 = $repositoryReleve->findByPm10MoyJour();
        $relevesPm25 = $repositoryReleve->findByPm25MoyJour();

        $formulaireFiltres = $this->createFormBuilder($saisieFiltres)
        ->add('titre', TextType::class)
        ->add('dateDebut', DateType::class, ['label' => 'Début', 'widget' => 'single_text'])
        ->add('dateFin', DateType::class, ['label' => 'Fin', 'widget' => 'single_text'])
        ->add('typeParticule', ChoiceType::class, ['label' => 'Particules', 'choices' => ['PM 2.5' => 'pm25', 'PM 10' => 'pm10'], 'expanded' => true, 'multiple' => true, 'empty_data' => ['pm25', 'pm10']])
        ->add('capteurs', ChoiceType::class, ['label' => 'Capteurs', 'choices' => ['Capteur 1' => 'Capteur1', 'Capteur 2' => 'Capteur2', 'Capteur 3' => 'Capteur3'], 'expanded' => false, 'multiple' => true])
        ->add('typeGraphique', ChoiceType::class, ['label' => 'Type de graphique', 'choices' => ['Barre' => 'bar', 'Ligne' => 'line', 'Radar' => 'radar', 'Secteur' => 'pie', 'Donut' => 'doughnut', 'Polaire' => 'polarArea'], 'expanded' => true, 'multiple' => false])
        ->getForm();
        
        $formulaireFiltres->handleRequest($requete);
        
        if($formulaireFiltres->isSubmitted() && $formulaireFiltres->isValid()){       
            $filtres = $formulaireFiltres->getData();

            if(sizeof($filtres['typeParticule']) > 1){
                $relevesPm25 = $repositoryReleve->findByPm25($filtres['dateDebut'], $filtres['dateFin'], $filtres['capteurs']);
                $relevesPm10 = $repositoryReleve->findByPm10($filtres['dateDebut'], $filtres['dateFin'], $filtres['capteurs']);
                return $this->render('capteurs_pollution/genererGraphique.html.twig', ['selectionFiltres' => $formulaireFiltres->createView(), 'relevesPm10' => $relevesPm10, 'relevesPm25' => $relevesPm25, 'typeGraphique' => $filtres['typeGraphique']]);
            }elseif ($filtres['typeParticule'][0] == 'pm25') {
                $relevesPm25 = $repositoryReleve->findByPm25($filtres['dateDebut'], $filtres['dateFin'], $filtres['capteurs']);
                return $this->render('capteurs_pollution/genererGraphique.html.twig', ['selectionFiltres' => $formulaireFiltres->createView(), 'relevesPm10' => null, 'relevesPm25' => $relevesPm25, 'typeGraphique' => $filtres['typeGraphique']]);
            }else{
                $relevesPm10 = $repositoryReleve->findByPm10($filtres['dateDebut'], $filtres['dateFin'], $filtres['capteurs']);
                return $this->render('capteurs_pollution/genererGraphique.html.twig', ['selectionFiltres' => $formulaireFiltres->createView(), 'relevesPm10' => $relevesPm10, 'relevesPm25' => null, 'typeGraphique' => $filtres['typeGraphique']]);
            }
            
        }   
        
        return $this->render('capteurs_pollution/genererGraphique.html.twig', ['selectionFiltres' => $formulaireFiltres->createView(), 'relevesPm10' => $relevesPm10, 'relevesPm25' => $relevesPm25, 'typeGraphique' => 'line']);
    }

}
