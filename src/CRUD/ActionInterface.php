<?php

declare(strict_types=1);

namespace App\CRUD;

use Symfony\Component\HttpFoundation\JsonResponse;

interface ActionInterface
{
    public function do(): JsonResponse;
}