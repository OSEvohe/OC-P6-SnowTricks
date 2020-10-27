<?php


namespace App\Validator\Constraints;


use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class MediaImageOrVideoValidator extends ConstraintValidator
{

    /**
     * @inheritDoc
     */
    public function validate($media, Constraint $constraint)
    {
        var_dump($media);
        // TODO: Implement validate() method.
    }
}