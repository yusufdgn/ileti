<?php


namespace App\Service\Validation;


use Symfony\Component\HttpFoundation\Exception\BadRequestException;
use Symfony\Component\Validator\Exception\ValidatorException;
use Symfony\Component\Validator\Validation;

class ValidationService
{
    public function validate($content, $constraints)
    {
        try {

            $validator = Validation::createValidator();
            if ($content === null) {
                throw new BadRequestException("Invalid json.");
            }

            $violations = $validator->validate($content, $constraints);

            if (0 !== count($violations)) {
                $violationArray = [];

                foreach ($violations as $violation) {
                    $violationArray[] = [
                        str_replace(
                            ']',
                            '',
                            str_replace('[', '', $violation->getPropertyPath())
                        ) => $violation->getMessage(),
                    ];
                }

                throw new ValidatorException(json_encode($violationArray));
            }
        } catch (\Exception $exception) {
            dd($exception);
        }
    }

}