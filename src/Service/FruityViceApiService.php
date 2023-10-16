<?php

namespace App\Service;

use App\Entity\Fruit;
use App\Entity\Nutrition;
use App\Exception\ServiceException;
use Psr\Log\LoggerInterface;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\DecodingExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class FruityViceApiService
{
    public const HOST = 'https://fruityvice.com';

    public const ALL_FRUITS_ENDPOINT = '/api/fruit/all';

    /**
     * @var HttpClientInterface
     */
    private $httpClient;

    /**
     * @param HttpClientInterface $httpClient
     */
    public function __construct(HttpClientInterface $httpClient)
    {
        $this->httpClient = $httpClient;
    }

    /**
     * @throws TransportExceptionInterface
     * @throws ServerExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws DecodingExceptionInterface
     * @throws ClientExceptionInterface
     */
    public function fetchFruits(): array
    {
        $fruits = [];
        $response = $this->httpClient->request('GET', sprintf('%s%s', self::HOST, self::ALL_FRUITS_ENDPOINT));
        if (200 === $response->getStatusCode()) {
            $fruitArr = $response->toArray();
            foreach ($fruitArr as $fruit) {
                $nutrition = new Nutrition(null,
                    $fruit['nutritions']['carbohydrates'],
                    $fruit['nutritions']['protein'],
                    $fruit['nutritions']['fat'],
                    $fruit['nutritions']['calories'],
                    $fruit['nutritions']['sugar']
                );

                $fruits[] = new Fruit($fruit['id'], $fruit['name'], $fruit['family'], $fruit['genus'], $fruit['order'], 0, $nutrition);
            }
        } else {
            $message = sprintf('Error code: %s. Error message: %s', $response->getStatusCode(), $response->getContent());
            throw new ServiceException($message);
        }

        return $fruits;
    }
}
