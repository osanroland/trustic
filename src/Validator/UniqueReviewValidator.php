<?php

namespace App\Validator;

use App\Entity\Review;
use App\Repository\ReviewRepository;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

class UniqueReviewValidator extends ConstraintValidator
{
    public function __construct(private readonly ReviewRepository $repository) {}

    public function validate(mixed $review, Constraint $constraint): void
    {
        if (!$constraint instanceof UniqueReview) {
            throw new UnexpectedTypeException($constraint, UniqueReview::class);
        }

        if (!$review instanceof Review) {
            throw new UnexpectedTypeException($review, Review::class);
        }

        if ($this->repository->hasReviewFromEmail($review->getAuthorEmail(), $review->getCompanyName())) {
            $this->context->buildViolation($constraint->message)
                ->addViolation();
        }
    }
}
