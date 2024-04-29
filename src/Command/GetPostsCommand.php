<?php

namespace App\Command;

use App\Entity\Post;
use App\Service\AuthorServiceInterface;
use App\Service\PostService;
use App\Service\PostServiceInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Contracts\HttpClient\HttpClientInterface;

#[AsCommand(
    name: 'app:get-posts',
    description: 'Get posts from API and save them to database',
)]
class GetPostsCommand extends Command
{
    public function __construct(
        private PostServiceInterface $postService,
        private AuthorServiceInterface $authorService
    )
    {
        parent::__construct();
    }

    protected function configure(): void
    {

    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $posts = $this->postService->getPosts();

        $authors = $this->authorService->getAuthors();

        $this->postService->savePosts($posts, $authors);

        $io->success('Posts have been saved to database');

        return Command::SUCCESS;
    }
}
