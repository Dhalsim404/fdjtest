<?php

namespace App\Controller;

use App\Service\ServiceDrawsApi;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class EuromillionsController extends AbstractController
{
    #[Route('/euromillions', name: 'euromillions_resultat')]
    public function index(ServiceDrawsApi $drawsApi): Response
    {
        $draw = $drawsApi->getDrawsEuroMillions();
        
        return $this->render('euromillions/result.twig', [
            'result' => $draw,
        ]);
    }
}
