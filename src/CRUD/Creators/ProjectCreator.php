<?php

declare(strict_types=1);

namespace App\CRUD\Creators;

use App\CRUD\AbstractAction;
use App\Entity\Project;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

final class ProjectCreator extends AbstractAction
{
    private ValidatorInterface $validator;

    private EntityManagerInterface $entityManager;

    public function __construct(
        RequestStack $requestStack,
        SerializerInterface $serializer,
        ValidatorInterface $validator,
        EntityManagerInterface $entityManager
    ) {
        parent::__construct($requestStack, $serializer);

        $this->validator = $validator;
        $this->entityManager = $entityManager;
    }

    public function do(): JsonResponse
    {
        $jsonResponse = new JsonResponse();

        $project = $this->getProjectFromRequest();

        $validations = $this->validator->validate($project);

        if (!$validations->count()) {
            $this->entityManager->persist($project);
            $this->entityManager->flush();

            $jsonResponse->setJson($this->serialize($project));
        } else {
            $jsonValidations = $this->serializer->serialize($validations, 'json');

            $jsonResponse->setJson($jsonValidations);
        }

        return $jsonResponse;
    }

    private function getProjectFromRequest(): Project
    {
        $jsonContent = $this->request->getContent();

        return $this->serializer->deserialize($jsonContent, Project::class, 'json');
    }
}