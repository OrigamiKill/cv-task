<?php

declare(strict_types=1);

namespace App\CRUD\Updaters;

use App\CRUD\AbstractAction;
use App\Entity\Project;
use App\Repository\ContactRepository;
use App\Repository\ProjectRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

final class ProjectUpdater extends AbstractAction
{
    private ValidatorInterface $validator;

    private EntityManagerInterface $entityManager;

    private ContactRepository $contactRepository;

    private ProjectRepository $projectRepository;

    public function __construct(
        RequestStack $requestStack,
        SerializerInterface $serializer,
        ValidatorInterface $validator,
        EntityManagerInterface $entityManager,
        ContactRepository $contactRepository,
        ProjectRepository $projectRepository
    ) {
        parent::__construct($requestStack, $serializer);

        $this->validator = $validator;
        $this->entityManager = $entityManager;
        $this->contactRepository = $contactRepository;
        $this->projectRepository = $projectRepository;
    }

    public function do(): JsonResponse
    {
        $jsonResponse = new JsonResponse();

        $project = $this->getProjectFromRequest();

        $projectValidationResult = $this->getJsonProjectValidationResult($project);

        if (empty($projectValidationResult)) {
            $project = $this->updateProjectWithRelations($project);

            $this->entityManager->flush();

            $jsonResponse->setJson($this->serialize($project));
        } else {
            $jsonResponse->setJson($projectValidationResult);
        }

        return $jsonResponse;
    }

    private function getJsonProjectValidationResult(Project $project): string
    {
        $validations = $this->validator->validate($project);

        return $validations->count() ? $this->serializer->serialize($validations, 'json') : '';
    }

    private function updateProjectWithRelations(Project $project): Project
    {
        $projectFromDB = $this->projectRepository->find($this->request->attributes->get('id'));

        if (null === $projectFromDB) {
            throw new NotFoundHttpException(sprintf('Project with id %d not found.', $projectFromDB->getId()));
        }

        foreach ($project->getContacts() as $contact) {
            $contactFromDB = $projectFromDB->getContactById($contact->getId());

            if (null === $contactFromDB) {
                throw new NotFoundHttpException(sprintf('Contact with id %d and Project id %d not found.', $contact->getId(), $projectFromDB->getId()));
            }

            $contactFromDB->update(
                $contact->getFirstName(),
                $contact->getLastName(),
                $contact->getPhone(),
            );
        }

        $projectFromDB->update(
            $project->getName(),
            $project->getUrl(),
        );

        return $projectFromDB;
    }

    private function getProjectFromRequest(): Project
    {
        $jsonContent = $this->request->getContent();

        return $this->serializer->deserialize($jsonContent, Project::class, 'json');
    }
}