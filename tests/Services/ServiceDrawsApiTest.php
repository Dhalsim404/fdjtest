<?php

namespace App\Service;

use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Contracts\HttpClient\ResponseInterface;

class ServiceDrawsApiTest extends TestCase
{
    const DRAWS_EUROMILLIONS = 'https://www.fdj.fr/api/service-draws/v1/games/euromillions/draws?include=results,addons&range=0-0';

    private $client;

    private $response;

    public function setUp() :void
    {
        $this->client = $this->createMock(HttpClientInterface::class, ['request']);
        $this->response = $this->createMock(ResponseInterface::class);
        $this->entity = new ServiceDrawsApi($this->client);
    }

    public function testGetDrawsEuroMillions()
    {
        $fakeData = '[{"something":"else", "drawn_at":"17-02-2121T23:32:12", "results":[{"a":"b","c":"d"}], "more":[{"stuff":"a","and":"b"}],"addons":[{"value":"abc"}]}]';
        $fakeResult = [
            'date' => 'Lundi 17 Février 2021',
            'numbers' => [
                'a' => 'b',
                'c' => 'd',
            ],
            'combinaison' => 'abc',
        ];

        $this->client->expects($this->once())
            ->method('request')
            ->with(Request::METHOD_GET, self::DRAWS_EUROMILLIONS)
            ->willReturn($this->response);

        $this->response->expects($this->once())
            ->method('getStatusCode')
            ->willReturn(Response::HTTP_OK);
    
        $this->response->expects($this->once())
            ->method('getContent')
            ->willReturn($fakeData);

        $result = $this->entity->getDrawsEuroMillions();

        $this->assertEquals($fakeResult, $result);
    }
}
