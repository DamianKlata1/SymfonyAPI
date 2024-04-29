<?php

namespace App\Service;

use App\Entity\Post;

interface PostServiceInterface
{
    public function getPosts(): array;
    public function savePosts(array $posts, array $authors): void;
    public function deletePost(Post $post): void;
}