<?php


namespace App\Validator\Constraints;


use Symfony\Component\Validator\Constraint;



/**
 * @Annotation
 */

class MediaImageOrVideo extends Constraint
{
    public $message = 'Un fichier image OU une URL de vidéo valide doit être renseigné';

    public function getTargets()
    {
        return self::CLASS_CONSTRAINT;
    }
}