<?php

namespace App\Validator;

use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;
use Symfony\Component\Validator\Exception\UnexpectedValueException;

class ContainsUserEmailValidator extends ConstraintValidator
{

    /**
     * @var UserRepository
     */
    private $userRepository;

    /**
     * @param UserRepository $userRepository
     */
    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }


    public function validate($user, Constraint $constraint)
    {
        if (!$constraint instanceof ContainsUserEmail) {
            throw new UnexpectedTypeException($constraint, ContainsUserEmail::class);
        }

        if (!$user instanceof User) {
            return;
        }

        $email = $user->getEmail();

        // custom constraints should ignore null and empty values to allow
        // other constraints (NotBlank, NotNull, etc.) to take care of that
        if (null === $email || '' === $email) {
            return;
        }

        if (!is_string($email)) {
            // throw this exception if your validator cannot handle the passed type so that it can be marked as invalid
            throw new UnexpectedValueException($email, 'string');
        }

        $isUserOrNull = $this->userRepository->verifEmailUser($email, $user->getId());
        if ($isUserOrNull) {
            $this->context->buildViolation($constraint->message)
                ->setParameter('{{ string }}', $email)
                ->addViolation();
        }


    }
}