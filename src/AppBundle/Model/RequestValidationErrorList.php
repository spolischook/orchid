<?php

namespace AppBundle\Model;

use Symfony\Component\Validator\ConstraintViolationInterface;
use Symfony\Component\Validator\ConstraintViolationListInterface;
use JMS\Serializer\Annotation as JMS;

/**
 * Store Request validation errors
 */
class RequestValidationErrorList
{
    /**
     * @var array ['fieldName' => ['Value should be a number', 'Value should be greater then 5']
     * @JMS\Type("array<string, array>")
     */
    private $queryErrors;

    public function getQueryErrors(): array
    {
        return $this->queryErrors;
    }

    public function addQueryErrors(string $field, ConstraintViolationListInterface $violationList)
    {
        /** @var ConstraintViolationInterface $error */
        foreach ($violationList as $error) {
            $this->queryErrors[$field][] = $error->getMessage();
        }
    }

    public function hasErrors(): bool
    {
        return (bool)$this->queryErrors;
    }
}
