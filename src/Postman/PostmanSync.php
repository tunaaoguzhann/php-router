<?php

namespace App\Postman;

use App\Interfaces\PostmanSyncInterface;
use GuzzleHttp\Client;
use App\Postman\PostmanFormatter;

class PostmanSync implements PostmanSyncInterface
{
    private Client $client;
    private PostmanFormatter $formatter;

    public function __construct(
        private string $apiKey,
        private string $collectionId
    ) {
        $this->client = new Client([
            'base_uri' => 'https://api.getpostman.com',
            'headers' => [
                'X-Api-Key' => $this->apiKey,
                'Content-Type' => 'application/json'
            ],
            'verify' => false
        ]);

        $this->formatter = new PostmanFormatter();
    }

    public function updateCollection(array $routes): void
    {
        try {
            $collection = $this->formatter->formatCollection($routes);

            $response = $this->client->put("/collections/{$this->collectionId}", [
                'json' => [
                    'collection' => $collection
                ]
            ]);

            if ($response->getStatusCode() === 200) {
                echo "Postman collection updated successfully!\n";
            }
        } catch (\Exception $e) {
            echo "Error updating Postman collection: " . $e->getMessage() . "\n";
        }
    }
}