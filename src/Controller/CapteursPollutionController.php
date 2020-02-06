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
}
