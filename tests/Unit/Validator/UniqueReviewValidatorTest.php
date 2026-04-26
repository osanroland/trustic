<?php

namespace App\Tests\Unit\Validator;

use App\Entity\Review;
use App\Repository\ReviewRepository;
use App\Validator\UniqueReview;
use App\Validator\UniqueReviewValidator;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Validator\Context\ExecutionContextInterface;
use Symfony\Component\Validator\Violation\ConstraintViolationBuilderInterface;

class UniqueReviewValidatorTest extends TestCase
{
    private ReviewRepository $repository;
    private UniqueReviewValidator $validator;
    private ExecutionContextInterface $context;

    protected function setUp(): void
    {
        $this->repository = $this->createStub(ReviewRepository::class);
        $this->validator = new UniqueReviewValidator($this->repository);

        $this->context = $this->createMock(ExecutionContextInterface::class);
        $this->validator->initialize($this->context);
    }

    public function testNoViolationWhenEmailAndCompanyAreUnique(): void
    {
        $this->repository
            ->method('hasReviewFromEmail')
            ->willReturn(false);

        $this->context
            ->expects($this->never())
            ->method('buildViolation');

        $this->validator->validate($this->buildReview('test@example.com', 'Apple'), new UniqueReview());
    }

    public function testViolationWhenEmailAlreadyReviewedCompany(): void
    {
        $this->repository
            ->method('hasReviewFromEmail')
            ->willReturn(true);

        $violationBuilder = $this->createStub(ConstraintViolationBuilderInterface::class);
        $violationBuilder->method('addViolation')->willReturnSelf();

        $this->context
            ->expects($this->once())
            ->method('buildViolation')
            ->willReturn($violationBuilder);

        $this->validator->validate($this->buildReview('test@example.com', 'Apple'), new UniqueReview());
    }

    private function buildReview(string $email, string $companyName): Review
    {
        $review = new Review();
        $review->setAuthorEmail($email);
        $review->setCompanyName($companyName);
        $review->setRating(4);
        $review->setReviewText('Tesztvélemény');

        return $review;
    }
}
