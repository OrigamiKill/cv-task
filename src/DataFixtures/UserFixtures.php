<?php

declare(strict_types=1);

namespace App\DataFixtures;

use App\Entity\User;
use App\Services\ApiKeyGenerator;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

final class UserFixtures extends Fixture
{
    private UserPasswordEncoderInterface $passwordEncoder;

    private ApiKeyGenerator $apiKeyGenerator;

    public function __construct(UserPasswordEncoderInterface $passwordEncoder, ApiKeyGenerator $apiKeyGenerator)
    {
        $this->passwordEncoder = $passwordEncoder;
        $this->apiKeyGenerator = $apiKeyGenerator;
    }

    public function load(ObjectManager $manager): void
    {
        $user = new User();

        $encodedPassword = $this->passwordEncoder->encodePassword($user, '12345678');
        $apiKeyData = $this->apiKeyGenerator->generate();

        $user->setEmail('test@example.com')
            ->setPassword($encodedPassword)
            ->setApiKey($apiKeyData[ApiKeyGenerator::API_KEY])
            ->setApiKeyExpiredDate($apiKeyData[ApiKeyGenerator::EXPIRED_DATE])
            ->addRole('ROLE_API');

        $manager->persist($user);

        $manager->flush();
    }
}
