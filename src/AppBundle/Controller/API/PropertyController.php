<?php

namespace AppBundle\Controller\API;

use AppBundle\Entity\Property;
use AppBundle\Model\RequestValidationErrorList;
use Doctrine\Bundle\DoctrineBundle\Registry as DoctrineRegistry;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Request\ParamFetcher;
use JMS\Serializer\Serializer;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Validator\Constraints\GreaterThanOrEqual;
use Symfony\Component\Validator\Constraints\LessThanOrEqual;
use Symfony\Component\Validator\Constraints\NotNull;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * @Rest\RouteResource("Property")
 */
class PropertyController extends FOSRestController
{
    /**
     * @var DoctrineRegistry
     */
    private $registry;

    /**
     * @var ValidatorInterface
     */
    private $validator;

    /**
     * @var Serializer
     */
    private $serializer;

    /**
     * @param DoctrineRegistry $registry
     */
    public function __construct(DoctrineRegistry $registry, ValidatorInterface $validator, Serializer $serializer)
    {
        $this->registry = $registry;
        $this->validator = $validator;
        $this->serializer = $serializer;
    }

    /**
//     * @Rest\View(serializerGroups={"property_list"})
     * @Rest\QueryParam(name="year", nullable=true, description="By default current year")
     * @Rest\QueryParam(name="month", nullable=true, description="By default current month")
     * @param ParamFetcher $paramFetcher
     */
    public function calendarAction(ParamFetcher $paramFetcher)
    {
        $month = $paramFetcher->get('month');
        $year = $paramFetcher->get('year');

        $requestErrors = new RequestValidationErrorList();
        $requestErrors->addQueryErrors(
            'month',
            $this->validator->validate(
                $month,
                [new LessThanOrEqual(12), new GreaterThanOrEqual(1), new NotNull()]
            )
        );

        $requestErrors->addQueryErrors(
            'year',
            $this->validator->validate(
                $year,
                [new LessThanOrEqual(2099), new GreaterThanOrEqual(2016), new NotNull()]
            )
        );

        if ($requestErrors->hasErrors()) {
            return new Response($this->serializer->serialize($requestErrors, 'json'), 400);
        }

        return $this->registry
            ->getManager()
            ->getRepository(Property::class)
            ->findAll();
    }
}
