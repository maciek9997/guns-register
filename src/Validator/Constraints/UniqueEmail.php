<?php
/**
 * Unique Tag constraint.
 */
namespace Validator\Constraints;

use Symfony\Component\Validator\Constraint;

/**
 * Class UniqueTag.
 */
class UniqueEmail extends Constraint
{
    /**
     * Message.
     *
     * @var string $message
     */
    public $message = '{{ email }} is not unique Email.';

    /**
     * User repository.
     *
     * @var null|\Repository\UserRepository $repository
     */
    public $repository = null;

}