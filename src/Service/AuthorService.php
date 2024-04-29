<?php

namespace App\Service;

use App\Exception\ApiFetchFailedException;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use App\Author;

class AuthorService implements AuthorServiceInterface
{
    public function __construct(
        private HttpClientInterface $client,
    )
    {
    }

    public function getAuthors(): array
    {
        try {
            $apiResponse = $this->fetchAuthorsFromApi();
            return $this->mapApiResponseToAuthors($apiResponse);
        } catch (\Exception $e) {
            throw new ApiFetchFailedException();
        }
    }


    public function getAuthorNameById(int $id, array $authors): string
    {
        return array_reduce($authors, function ($carry, $author) use ($id) {
            if ($author->getId() === $id) {
                return $author->getName();
            }
            return $carry;
        }, '');
    }

    private function fetchAuthorsFromApi(): array
    {
        $apiUrl = 'https://jsonplaceholder.typicode.com/users';
        return $this->client->request('GET', $apiUrl)->toArray();
    }

    private function mapApiResponseToAuthors(array $apiResponse): array
    {
        return array_map(function ($authorData) {
            return new Author($authorData['id'], $authorData['name']);
        }, $apiResponse);
    }
}