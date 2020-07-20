<?php

declare(strict_types=1);

namespace App\Controller;

use App\CRUD\Creators\ProjectCreator;
use App\CRUD\Updaters\ProjectUpdater;
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

    /**
     * @Route(
     *     "/projects/{id}",
     *     methods={"PATCH"},
     *     format="json",
     *     requirements={"id"="\d+"},
     *     name="app_api_update_project"
     * )
     */
    public function updateProject(ProjectUpdater $projectUpdater): JsonResponse
    {
        return $projectUpdater->do();
    }
}