<?php

declare(strict_types=1);

namespace App\Services;

use App\Services\Filters\Project\ProjectBudgetFilter;
use App\Services\Filters\Project\ProjectCodeFilter;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Query\FilterCollection;

class FiltersManager
{
    private const PROJECT_CODE_FILTER_NAME = 'project_code_filter';
    private const PROJECT_BUDGET_FILTER_NAME = 'project_budget_filter';

    private FilterCollection $filters;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->filters = $entityManager->getFilters();
    }

    public function enableProjectFilters(string $code = null, string $budgetFrom = null, string $budgetTo = null)
    {
        $this->filters
            ->enable(self::PROJECT_CODE_FILTER_NAME)
            ->setParameter(ProjectCodeFilter::FIELD_NAME, $code);

        $this->filters
            ->enable(self::PROJECT_BUDGET_FILTER_NAME)
            ->setParameter(ProjectBudgetFilter::FILED_FROM_NAME, $budgetFrom)
            ->setParameter(ProjectBudgetFilter::FILED_TO_NAME, $budgetTo);
    }

    public function disableProjectFilters()
    {
        $this->filters->disable(self::PROJECT_CODE_FILTER_NAME);
        $this->filters->disable(self::PROJECT_BUDGET_FILTER_NAME);
    }
}