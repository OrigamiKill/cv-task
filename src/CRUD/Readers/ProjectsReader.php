<?php

declare(strict_types=1);

namespace App\CRUD\Readers;

use App\CRUD\AbstractAction;
use App\Repository\ProjectRepository;
use App\Services\FiltersManager;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Serializer\SerializerInterface;

final class ProjectsReader extends AbstractAction
{
    private FiltersManager $filtersManager;

    private ProjectRepository $projectRepository;

    public function __construct(
        RequestStack $requestStack,
        SerializerInterface $serializer,
        FiltersManager $filtersManager,
        ProjectRepository $projectRepository
    ) {
        parent::__construct($requestStack, $serializer);

        $this->filtersManager = $filtersManager;
        $this->projectRepository = $projectRepository;
    }

    public function do(): JsonResponse
    {
        $this->filtersManager->enableProjectFilters(
            $this->request->get('code'),
            $this->request->get('budgetFrom'),
            $this->request->get('budgetTo')
        );

        $projects = $this->projectRepository->findAll();

        $this->filtersManager->disableProjectFilters();

        $serializedProjects = $this->serialize($projects);

        return (new JsonResponse())->setJson($serializedProjects);
    }
}