<?php

declare(strict_types=1);

namespace App\Entity;

use App\Repository\ContactRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=ContactRepository::class)
 */
class Contact
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private int $id;

    /**
     * @ORM\Column(type="string")
     * @Assert\Length(
     *      min = 2,
     *      max = 50,
     *      minMessage = "Project first name must be at least {{ limit }} characters long",
     *      maxMessage = "Project first name cannot be longer than {{ limit }} characters",
     *      allowEmptyString = false
     * )
     */
    private string $firstName = '';

    /**
     * @ORM\Column(type="string")
     * @Assert\Length(
     *      min = 2,
     *      max = 50,
     *      minMessage = "Project first name must be at least {{ limit }} characters long",
     *      maxMessage = "Project first name cannot be longer than {{ limit }} characters",
     *      allowEmptyString = false
     * )
     */
    private string $lastName = '';

    /**
     * @ORM\Column(type="string")
     * @Assert\Length(
     *      min = 19,
     *      max = 19,
     *      minMessage = "Contact phone number must be at least {{ limit }} characters long",
     *      maxMessage = "Contact phone number cannot be longer than {{ limit }} characters",
     *      allowEmptyString = false
     * )
     */
    private string $phone = '';

    /**
     * @ORM\ManyToOne(targetEntity="Project", inversedBy="contacts", fetch="LAZY")
     */
    private Project $project;

    public function update(string $firstName, string $lastName, string $phone, Project $project = null): self
    {
        $this->firstName = $firstName;
        $this->lastName = $lastName;
        $this->phone = $phone;

        if (null !== $project) {
            $this->project = $project;
        }

        return $this;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(int $id): self
    {
        $this->id = $id;

        return $this;
    }

    public function getFirstName(): string
    {
        return $this->firstName;
    }

    public function setFirstName(string $firstName): self
    {
        $this->firstName = $firstName;

        return $this;
    }

    public function getLastName(): string
    {
        return $this->lastName;
    }

    public function setLastName(string $lastName): self
    {
        $this->lastName = $lastName;

        return $this;
    }

    public function getPhone(): string
    {
        return $this->phone;
    }

    public function setPhone(string $phone): self
    {
        $this->phone = $phone;

        return $this;
    }

    public function getProject(): Project
    {
        return $this->project;
    }

    public function setProject(Project $project): self
    {
        $this->project = $project;

        return $this;
    }
}