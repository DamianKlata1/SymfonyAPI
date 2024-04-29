<?php
namespace App\Service;

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
        $authorsArray = $this->client->request('GET', 'https://jsonplaceholder.typicode.com/users')->toArray();

        return array_map(function($author){
            return new Author($author['id'], $author['name']);
        }, $authorsArray);
    }
    public function getAuthorNameById(int $id, array $authors): string
    {
        return array_reduce($authors, function($carry, $author) use ($id){
            if($author->getId() === $id){
                return $author->getName();
            }
            return $carry;
        }, '');
    }
}