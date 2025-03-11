<?php

namespace App\Interfaces;

interface PostmanSyncInterface
{
    public function updateCollection(array $routes): void;
}