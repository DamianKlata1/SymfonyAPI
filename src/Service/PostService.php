<?php

namespace App\Service;

use App\Entity\Post;
use App\Exception\ApiFetchFailedException;
use App\Exception\DeleteFailedException;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Query;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\DecodingExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class PostService implements PostServiceInterface
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private HttpClientInterface    $client,
        private AuthorServiceInterface $authorService,
    )
    {
    }

    public function getPosts(): array
    {
        try {
            $posts = $this->fetchPostsFromApi();
            return $posts;
        } catch (\Exception $e) {
            throw new ApiFetchFailedException();
        }

    }

    public function savePosts(array $posts, array $authors): void
    {
        foreach ($posts as $postArray) {
            $post = $this->createPostFromData($postArray, $authors);
            $this->entityManager->persist($post);
        }
        $this->entityManager->flush();
    }

    public function deletePost(Post $post): void
    {
        try {
            $this->entityManager->remove($post);
            $this->entityManager->flush();
        } catch (\Exception $e) {
            throw new DeleteFailedException();
        }
    }

    public function getAllPostsQuery(): Query
    {
        return $this->entityManager->getRepository(Post::class)
            ->createQueryBuilder('p')
            ->getQuery();
    }

    private function fetchPostsFromApi(): array
    {
        $apiUrl = 'https://jsonplaceholder.typicode.com/posts';
        return $this->client->request('GET', $apiUrl)->toArray();
    }

    private function createPostFromData(array $postArray, array $authors): Post
    {
        $post = new Post();
        $post->setTitle($postArray['title']);
        $post->setBody($postArray['body']);
        $post->setAuthor($this->authorService->getAuthorNameById($postArray['userId'], $authors));
        return $post;
    }

}