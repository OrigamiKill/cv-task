<?php

declare(strict_types=1);

namespace App\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
final class ContainsAllowedDomains extends Constraint
{
    public string $message = "The string '{{ string }}' contains an not allowed domain.";
}