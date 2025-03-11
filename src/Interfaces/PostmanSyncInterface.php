<?php

namespace TunaOguzhan\Router\Interfaces;

interface PostmanSyncInterface
{
    public function updateCollection(array $routes): void;
}