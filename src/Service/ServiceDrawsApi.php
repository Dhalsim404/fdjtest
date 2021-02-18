<?php

namespace App\Service;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class ServiceDrawsApi
{
    const DRAWS_EUROMILLIONS = 'https://www.fdj.fr/api/service-draws/v1/games/euromillions/draws?include=results,addons&range=0-0';

    private $client;

    public function __construct(HttpClientInterface $client)
    {
        $this->client = $client;
    }

    public function getDrawsEuroMillions(): array
    {
        $response = $this->client->request(Request::METHOD_GET, self::DRAWS_EUROMILLIONS);

        if (Response::HTTP_OK !== $response->getStatusCode()) {
            return [];
        }

        $lastResult = $response->getContent();

        if (empty($lastResult)) {
            return [];
        }
        
        $lastResult = json_decode($lastResult, true);

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

        return $draw;
    }

}