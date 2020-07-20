<?php

declare(strict_types=1);

namespace App\Entity;

use App\Repository\ProjectRepository;
use App\Validator\Constraints as CustomAssert;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\Criteria;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=ProjectRepository::class)
 */
class Project
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private ?int $id = null;

    /**
     * @ORM\Column(type="string", length=50)
     * @Assert\Length(
     *      min = 5,
     *      max = 50,
     *      minMessage = "Project name must be at least {{ limit }} characters long",
     *      maxMessage = "Project name cannot be longer than {{ limit }} characters",
     *      allowEmptyString = false
     * )
     */
    private string $name = '';

    /**
     * @ORM\Column(type="string")
     * @Assert\Length(
     *      min = 3,
     *      max = 10,
     *      minMessage = "Project code must be at least {{ limit }} characters long",
     *      maxMessage = "Project code cannot be longer than {{ limit }} characters",
     *      allowEmptyString = false
     * )
     */
    private string $code = '';

    /**
     * @ORM\Column(type="string")
     * @Assert\NotBlank
     * @CustomAssert\ContainsAllowedDomains
     */
    private string $url = '';

    /**
     * @ORM\Column(type="integer")
     * @Assert\PositiveOrZero
     */
    private int $budget = 0;

    /**
     * @ORM\OneToMany(targetEntity="Contact", mappedBy="project", fetch="EAGER", cascade={"persist", "remove"})
     * @Assert\Valid
     * @Assert\Count(
     *      min = 1,
     *      minMessage = "You must specify at least one contact"
     * )
     */
    private Collection $contacts;

    public function __construct()
    {
        $this->contacts = new ArrayCollection();
    }

    public function update(string $name, string $url, Collection $contacts = null): self
    {
        $this->name = $name;
        $this->url = $url;

        if (null !== $contacts) {
            $this->setContacts($contacts);
        }

        return $this;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getCode(): string
    {
        return $this->code;
    }

    public function setCode(string $code): self
    {
        if (empty($this->code)) {
            $this->code = $code;
        }

        return $this;
    }

    public function getUrl(): string
    {
        return $this->url;
    }

    public function setUrl(string $url): self
    {
        $this->url = $url;

        return $this;
    }

    public function getBudget(): int
    {
        return $this->budget;
    }

    public function setBudget(int $budget): self
    {
        $this->budget = $budget;

        return $this;
    }

    public function getContacts(): Collection
    {
        return $this->contacts;
    }

    public function setContacts(Collection $contacts): self
    {
        $this->contacts = $contacts;

        foreach ($contacts as $contact) {
            $contact->setProject($this);
        }

        return $this;
    }

    public function addContact(Contact $contact): self
    {
        if (!$this->contacts->contains($contact)) {
            $this->contacts->add($contact);
            $contact->setProject($this);
        }

        return $this;
    }

    public function removeContact(Contact $contact): self
    {
        if ($this->contacts->contains($contact)) {
            $this->contacts->removeElement($contact);
        }

        return $this;
    }

    public function getContactById(int $contactId): ?Contact
    {
        $criteria = Criteria::create()->where(Criteria::expr()->eq('id', $contactId));

        return $this->contacts->matching($criteria)->first() ?: null;
    }
}