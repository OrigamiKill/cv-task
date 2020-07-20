<?php

declare(strict_types=1);

namespace App\DataFixtures;

use App\Entity\Contact;
use App\Entity\Project;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\Persistence\ObjectManager;

final class ProjectFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        for ($i = 0; $i < 10; ++$i) {
            $project = new Project();

            $data = $this->getDataForProject($i);

            $project->setName($data['name'])
                ->setCode($data['code'])
                ->setUrl($data['url'])
                ->setBudget($data['budget'])
                ->setContacts($data['contacts']);

            $manager->persist($project);
        }

        $manager->flush();
    }

    private function getDataForProject(int $number): array
    {
        $name = 'name_' . $number;
        $code = 'code_' . $number;
        $url = 'http://example.com/test_' . $number;
        $budget = 100 * $number;
        $contacts = $this->getContactsForProject($number);

        return ['name' => $name, 'code' => $code, 'url' => $url, 'budget' => $budget, 'contacts' => $contacts];
    }

    private function getContactsForProject(int $number): Collection
    {
        $contacts = new ArrayCollection();

        for ($i = 0; $i <= $number; ++$i) {
            $contact = new Contact();

            $data = $this->getDataForContact($i);

            $contact->setFirstName($data['firstName'])
                ->setLastName($data['lastName'])
                ->setPhone($data['phone']);

            $contacts->add($contact);
        }

        return $contacts;
    }

    private function getDataForContact(int $number): array
    {
        $firstName = 'firstName_' . $number;
        $lastName = 'lastName_' . $number;
        $phone = substr('+123 (45) 678-91-21' . $number, 0, 18);

        return ['firstName' => $firstName, 'lastName' => $lastName, 'phone' => $phone];
    }
}