<?php

declare(strict_types=1);

namespace App\CRUD\Removers;

use App\CRUD\AbstractAction;
use App\Entity\Project;
use App\Repository\ProjectRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\SerializerInterface;

final class ProjectRemover extends AbstractAction
{
    private ProjectRepository $projectRepository;

    private EntityManagerInterface $entityManager;

    public function __construct(
        RequestStack $requestStack,
        SerializerInterface $serializer,
        ProjectRepository $projectRepository,
        EntityManagerInterface $entityManager
    ) {
        parent::__construct($requestStack, $serializer);

        $this->entityManager = $entityManager;
        $this->projectRepository = $projectRepository;
    }

    public function do(): JsonResponse
    {
        $jsonResponse = new JsonResponse();

        $project = $this->projectRepository->find($this->request->attributes->get('id'));

        if (null === $project) {
            $jsonResponse->setStatusCode(Response::HTTP_NOT_FOUND);
        }

        $this->removeProject($project);

        return $jsonResponse;
    }

    private function removeProject(Project $project): void
    {
        $this->entityManager->remove($project);

        $this->entityManager->flush();
    }
}