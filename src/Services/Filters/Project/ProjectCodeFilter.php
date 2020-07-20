<?php

declare(strict_types=1);

namespace App\Services\Filters\Project;

use App\Entity\Project;
use Doctrine\ORM\Mapping\ClassMetadata;
use Doctrine\ORM\Query\Filter\SQLFilter;

final class ProjectCodeFilter extends SQLFilter
{
    public  const FIELD_NAME = 'code';
    private const PERCENT = '%';

    public function addFilterConstraint(ClassMetadata $targetEntity, $targetTableAlias): string
    {
        if (
            Project::class != $targetEntity->getReflectionClass()->name
            || "''" === $this->getParameter(self::FIELD_NAME)
        ) {
            return '';
        }

        $code = self::PERCENT . substr($this->getParameter(self::FIELD_NAME), 1, -1) . self::PERCENT;

        return sprintf(
            '%s.code LIKE "%s"',
            $targetTableAlias,
            $code
        );
    }
}