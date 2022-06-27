<?php

namespace App\Traits;

use Symfony\Component\Validator\ConstraintViolationListInterface;
use Symfony\Component\Validator\Validation;

trait EntityValidationTrait
{

    /**
     * @param $entity
     * @return ConstraintViolationListInterface
     */
    public function validateEntity($entity): ConstraintViolationListInterface
    {
        return
            Validation::createValidatorBuilder()
                ->enableAnnotationMapping()
                ->getValidator()
                ->validate($entity);
    }

}