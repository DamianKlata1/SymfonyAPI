<?php

namespace App\Controller;

use App\Entity\Post;
use App\Service\PostServiceInterface;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class PostController extends AbstractController
{


    public function __construct(
        private EntityManagerInterface $entityManager,
        private PaginatorInterface $paginator,
        private PostServiceInterface $postService
    )
    {
    }

    #[Route('/list', name: 'list')]
    public function index(Request $request): Response
    {
        $allPostsQuery = $this->entityManager->getRepository(Post::class)->createQueryBuilder('p')->getQuery();
        $pagination = $this->paginator->paginate(
            $allPostsQuery,
            $request->query->getInt('page', 1),
            10
        );


        return $this->render('post/index.html.twig', [
            'pagination' => $pagination,
        ]);
    }
    #[Route('/delete/{id}', name: 'post_delete')]
    public function delete(Post $post): Response
    {
        $this->postService->deletePost($post);

        return $this->redirectToRoute('list');
    }
}
