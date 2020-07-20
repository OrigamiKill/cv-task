<?php

declare(strict_types=1);

namespace App\CRUD\Security;

use App\CRUD\AbstractAction;
use App\Entity\User;
use App\Repository\UserRepository;
use App\Services\ApiKeyGenerator;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Serializer\SerializerInterface;

final class UserAuthorizer extends AbstractAction
{
    private EntityManagerInterface $entityManager;

    private UserRepository $userRepository;

    private UserPasswordEncoderInterface $passwordEncoder;

    private ApiKeyGenerator $apiKeyGenerator;

    public function __construct(
        RequestStack $requestStack,
        SerializerInterface $serializer,
        UserRepository $userRepository,
        EntityManagerInterface $entityManager,
        UserPasswordEncoderInterface $passwordEncoder,
        ApiKeyGenerator $apiKeyGenerator
    ) {
        parent::__construct($requestStack, $serializer);

        $this->entityManager = $entityManager;
        $this->userRepository = $userRepository;
        $this->passwordEncoder = $passwordEncoder;
        $this->apiKeyGenerator = $apiKeyGenerator;
    }

    public function do(): JsonResponse
    {
        $jsonResponse = new JsonResponse();

        try {
            $user = $this->getUserByEmail($this->request->get('email'));

            $validationJsonResult = $this->getJsonPasswordValidationResult($user, $this->request->get('password'));

            if (empty($validationJsonResult)) {
                $apiKeyData = $this->apiKeyGenerator->generate();

                $user->setApiKey($apiKeyData[ApiKeyGenerator::API_KEY])
                    ->setApiKeyExpiredDate($apiKeyData[ApiKeyGenerator::EXPIRED_DATE]);

                $apiKeyDataJson = $this->serializer->serialize($apiKeyData, 'json');

                $jsonResponse->setJson($apiKeyDataJson);

                $this->entityManager->flush();
            } else {
                $jsonResponse->setJson($validationJsonResult);
            }
        } catch (NotFoundHttpException $exception) {
            $jsonResponse->setStatusCode(Response::HTTP_NOT_FOUND);
            $jsonResponse->setData(['error' => $exception->getMessage()]);
        }

        return $jsonResponse;
    }

    private function getUserByEmail(string $email = null): User
    {
        $user = $this->userRepository->findOneBy(['email' => $email]);

        if (null === $user) {
            throw new NotFoundHttpException(sprintf('User with email %s not found.', $email));
        }

        return $user;
    }

    private function getJsonPasswordValidationResult(UserInterface $user, string $plainPassword): string
    {
        return $this->passwordEncoder->isPasswordValid($user, $plainPassword)
            ? ''
            : $this->serializer->serialize(['error' => 'Invalid password.'], 'json');
    }
}