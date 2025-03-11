<?php

namespace App\Interfaces;

interface RouterInterface
{
    public function get(string $path, mixed $handler = null, array $options = []): void;
    public function post(string $path, mixed $handler = null, array $options = []): void;
    public function put(string $path, mixed $handler = null, array $options = []): void;
    public function delete(string $path, mixed $handler = null, array $options = []): void;
}