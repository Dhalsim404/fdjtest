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
        //$draw = $drawsApi->getDrawsEuroMillions();
        $lastResult = json_decode('[{"eid":2020200121014,"este_id":459,"este_eid":21014,"este_game_eid":20,"este_game_version":202001,"este_game_theme":"neutre","drawn_at":"2021-02-16T21:30:00+01:00","foreclosure_at":"2021-05-18T00:00:00+02:00","draw_day_index":1,"draw_cycle_index":7,"este_funding_type":7,"published":true,"results":[{"id":2660228,"type":"number","value":"5","este_lotery":"fdj","draw_index":1},{"id":2660227,"type":"number","value":"9","este_lotery":"fdj","draw_index":1},{"id":2660225,"type":"number","value":"25","este_lotery":"fdj","draw_index":1},{"id":2660226,"type":"number","value":"29","este_lotery":"fdj","draw_index":1},{"id":2660224,"type":"number","value":"30","este_lotery":"fdj","draw_index":1},{"id":2660229,"type":"special","value":"6","este_lotery":"fdj","draw_index":1},{"id":2660230,"type":"special","value":"7","este_lotery":"fdj","draw_index":1}],"addons":[{"id":2660231,"type":"raffle","value":"RC 048 0036","este_lotery":"fdj","draw_index":1}]}]', true);

        if (!isset($lastResult[0]['drawn_at'], $lastResult[0]['results'], $lastResult[0]['addons'][0]['value'])) {
            return [];
        }

        setlocale(LC_TIME, 'fr_FR');
        $draw['date'] = new \DateTime($lastResult[0]['drawn_at']);
        $draw['date'] = $draw['date']->format('l j F Y');
        $draw['combinaison'] = $lastResult[0]['addons'][0]['value'];

        foreach ($lastResult[0]['results'] as $number) {
            $draw['numbers'][] = $number;
        }
//dump($draw);die;
        //return $draw;

        
        return $this->render('euromillions/result.twig', [
            'result' => $draw,
        ]);
    }
}
