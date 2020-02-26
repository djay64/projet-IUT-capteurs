<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

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
     * @Route("/genererGraphique", name="capteurs_pollution_genererGraphique")
     */
    public function genererGraphique(Request $requete){
        $saisieFiltres = ['titre' => 'Titre du graphique'];

        $formulaireFiltres = $this->createFormBuilder($saisieFiltres)
        ->add('titre', TextType::class)
        ->add('dateDebut', DateType::class, ['widget' => 'single_text'])
        ->add('dateFin', DateType::class, ['widget' => 'single_text'])
        ->add('typeParticule', ChoiceType::class, ['choices' => ['PM 2.5' => 'pm25', 'PM 10' => 'pm10'], 'expanded' => true, 'multiple' => true])
        ->add('capteurs', ChoiceType::class, ['choices' => ['Capteur 1' => 'capteur1', 'Capteur 2' => 'capteur2', 'Capteur 3' => 'capteur3'], 'expanded' => false, 'multiple' => true])
        ->add('typeGraphique', ChoiceType::class, ['choices' => ['Barre' => 'barre', 'Baton' => 'baton', 'Histogramme' => 'histogramme', 'Radar' => 'radar', 'Nuage de points' => 'nuage', 'Secteur' => 'secteur', 'Courbe' => 'courbe', 'Aires' => 'aire'], 'expanded' => true, 'multiple' => false])
        ->getForm();

        return $this->render('capteurs_pollution/genererGraphique.html.twig', ['selectionFiltres' => $formulaireFiltres->createView()]);
    }

}
