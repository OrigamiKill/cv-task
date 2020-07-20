<?php

declare(strict_types=1);

namespace App\Validator\Constraints;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;
use Symfony\Component\Validator\Exception\UnexpectedValueException;

final class ContainsAllowedDomainsValidator extends ConstraintValidator
{
    private array $allowedDomains;

    public function __construct(array $allowedDomains)
    {
        $this->allowedDomains = $allowedDomains;
    }

    public function validate($value, Constraint $constraint): void
    {
        if (!$constraint instanceof ContainsAllowedDomains) {
            throw new UnexpectedTypeException($constraint, ContainsAllowedDomains::class);
        }

        if (null === $value || '' === $value) {
            return;
        }

        if (!is_string($value)) {
            throw new UnexpectedValueException($value, 'string');
        }

        $domain = parse_url($value, PHP_URL_HOST);

        if (!in_array($domain, $this->allowedDomains)) {
            $this->context->buildViolation($constraint->message)
                ->setParameter('{{ string }}', $value)
                ->addViolation();
        }
    }
}