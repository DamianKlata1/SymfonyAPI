<?php

namespace App\Service;

interface AuthorServiceInterface
{
    public function getAuthors(): array;
    public function getAuthorNameById(int $id, array $authors): string;
}