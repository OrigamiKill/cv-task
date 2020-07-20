<?php

declare(strict_types=1);

namespace App\CRUD\Readers;

use App\CRUD\AbstractAction;
use App\Repository\ProjectRepository;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\SerializerInterface;

final class ProjectReader extends AbstractAction
{
    private ProjectRepository $projectRepository;

    public function __construct(
        RequestStack $requestStack,
        SerializerInterface $serializer,
        ProjectRepository $projectRepository
    ) {
        parent::__construct($requestStack, $serializer);

        $this->projectRepository = $projectRepository;
    }

    public function do(): JsonResponse
    {
        $jsonResponse = new JsonResponse();

        $project = $this->projectRepository->find($this->request->attributes->get('id'));

        if ($project) {
            $serializedProject = $this->serialize($project);

            $jsonResponse->setJson($serializedProject);
        } else {
            $jsonResponse->setStatusCode(Response::HTTP_NOT_FOUND);
        }

        return $jsonResponse;
    }
}