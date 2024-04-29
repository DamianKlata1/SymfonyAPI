<?php

namespace App\Controller;

use App\Entity\Post;
use App\Exception\DeleteFailedException;
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
        private PaginatorInterface $paginator,
        private PostServiceInterface $postService
    )
    {
    }

    #[Route('/list', name: 'list')]
    public function index(Request $request): Response
    {
        $allPostsQuery = $this->postService->getAllPostsQuery();
        $posts = $this->paginator->paginate(
            $allPostsQuery,
            $request->query->getInt('page', 1),
            10
        );
        return $this->render('post/list.html.twig', [
            'posts' => $posts,
        ]);
    }
    #[Route('/delete/{id}', name: 'post_delete')]
    public function delete(Post $post): Response
    {
        try {
            $this->postService->deletePost($post);
            $this->addFlash('success', 'Post deleted successfully.');
        } catch (DeleteFailedException  $e) {
            $this->addFlash('error', $e->getMessage());
        }
        return $this->redirectToRoute('list');
    }
}
