<?php

declare(strict_types=1);

namespace App\CRUD;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Serializer\SerializerInterface;

abstract class AbstractAction implements ActionInterface
{
    protected ?Request $request;

    protected SerializerInterface $serializer;

    public function __construct(
        RequestStack $requestStack,
        SerializerInterface $serializer
    ) {
        $this->request = $requestStack->getCurrentRequest();
        $this->serializer = $serializer;
    }

    /**
     * @param mixed $data
     */
    protected function serialize($data): string
    {
        return $this->serializer->serialize($data, 'json', ['ignored_attributes' => ['project']]);
    }
}