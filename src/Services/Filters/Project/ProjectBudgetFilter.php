<?php

declare(strict_types=1);

namespace App\Services\Filters\Project;

use App\Entity\Project;
use Doctrine\ORM\Mapping\ClassMetadata;
use Doctrine\ORM\Query\Filter\SQLFilter;

final class ProjectBudgetFilter extends SQLFilter
{
    public const FILED_FROM_NAME = 'budgetFrom';
    public const FILED_TO_NAME = 'budgetTo';

    public function addFilterConstraint(ClassMetadata $targetEntity, $targetTableAlias): string
    {
        if (
            Project::class != $targetEntity->getReflectionClass()->name
            || "''" === $this->getParameter(self::FILED_FROM_NAME)
            || "''" === $this->getParameter(self::FILED_TO_NAME)
        ) {
            return '';
        }

        return sprintf(
            '%s.budget BETWEEN %s AND %s',
            $targetTableAlias,
            $this->getParameter(self::FILED_FROM_NAME),
            $this->getParameter(self::FILED_TO_NAME)
        );
    }
}