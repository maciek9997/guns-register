<?php
/**
 * Unique Email validator.
 */
namespace Validator\Constraints;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

/**
 * Klasa Validator walidatora unikalności adresu email
 * Class UniqueEmailValidator
 */
class UniqueEmailValidator extends ConstraintValidator
{
    /**
     * @param mixed      $value
     * @param Constraint $constraint
     */
    public function validate($value, Constraint $constraint)
    {

        if (!$constraint->repository) {
            return;
        }

        $result = $constraint->repository->getUserByLogin($value);

        if ($result) {
            $this->context->buildViolation($constraint->message)
                ->setParameter('{{ email }}', $value)
                ->addViolation();
        }
    }
}
