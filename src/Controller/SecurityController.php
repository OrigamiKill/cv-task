<?php

declare(strict_types=1);

namespace App\Controller;

use App\CRUD\Security\UserAuthorizer;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

final class SecurityController extends AbstractController
{
    /**
     * @Route("/authorize", methods={"GET"}, format="json", name="app_api_authorize")
     */
    public function generateNewApiKey(UserAuthorizer $userAuthorizer): JsonResponse
    {
        return $userAuthorizer->do();
    }
}