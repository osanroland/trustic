<?php

namespace App\Validator;

use Symfony\Component\Validator\Constraint;

#[\Attribute(\Attribute::TARGET_CLASS)]
class UniqueReview extends Constraint
{
    public string $message = 'Erről az email címről már érkezett vélemény erre a cégre.';

    public function getTargets(): string
    {
        return self::CLASS_CONSTRAINT;
    }
}
