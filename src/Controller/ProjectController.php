<?php

declare(strict_types=1);

namespace App\Controller;

use App\CRUD\Creators\ProjectCreator;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class ProjectController extends AbstractController
{
    /**
     * @Route("/projects", methods={"POST"}, format="json", name="app_api_create_project")
     */
    public function createProject(ProjectCreator $projectCreator): JsonResponse
    {
        return $projectCreator->do();
    }
}