<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class GroupeDeCompetenceController extends AbstractController
{
    /**
     * @Route("/groupe/de/competence", name="groupe_de_competence")
     */
    public function index()
    {
        return $this->render('groupe_de_competence/index.html.twig', [
            'controller_name' => 'GroupeDeCompetenceController',
        ]);
    }
}
