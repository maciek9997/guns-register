<?php
/**
 * Unique Tag constraint.
 */
namespace Validator\Constraints;

use Symfony\Component\Validator\Constraint;

/**
 * Class UniqueTag.
 */

//Klasa Constraints walidatora unikalności adresu email

class UniqueEmail extends Constraint
{
    /**
     * Message.
     *
     * @var string $message
     */

    public $message = '{{ email }} message.mail_not_unique';

    /**
     * User repository.
     *
     * @var null|\Repository\UserRepository $repository
     */
    public $repository = null;

}