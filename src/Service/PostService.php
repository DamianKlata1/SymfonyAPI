<?php

namespace App\Service;

use App\Entity\Post;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class PostService implements PostServiceInterface
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private HttpClientInterface $client,
        private AuthorServiceInterface $authorService,
        private SerializerInterface $serializer
    )
    {
    }

    public function getPosts(): array
    {
        $response = $this->client->request('GET', 'https://jsonplaceholder.typicode.com/posts')->toArray();
        return $response;
    }
    public function savePosts(array $posts, array $authors): void
    {
        foreach($posts as $postData){
            $post = new Post();
            $post->setTitle($postData['title']);
            $post->setBody($postData['body']);
            $post->setAuthor($this->authorService->getAuthorNameById($postData['userId'], $authors));
            $this->entityManager->persist($post);
        }
        $this->entityManager->flush();
    }
    public function deletePost(Post $post): void
    {
        $this->entityManager->remove($post);
        $this->entityManager->flush();
    }
}