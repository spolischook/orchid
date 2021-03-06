<?php

namespace AppBundle\Controller\API;

use AppBundle\Entity\Property;
use AppBundle\Model\RequestValidationErrorList;
use AppBundle\Repository\PropertyCalendarRepository;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Request\ParamFetcher;
use JMS\Serializer\SerializationContext;
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
     * @var PropertyCalendarRepository
     */
    private $propertyCalendarRepository;

    /**
     * @var ValidatorInterface
     */
    private $validator;

    /**
     * @var Serializer
     */
    private $serializer;

    /**
     * @param PropertyCalendarRepository $propertyCalendarRepository
     */
    public function __construct(
        PropertyCalendarRepository $propertyCalendarRepository,
        ValidatorInterface $validator,
        Serializer $serializer
    ) {
        $this->propertyCalendarRepository = $propertyCalendarRepository;
        $this->validator = $validator;
        $this->serializer = $serializer;
    }

    /**
     * @Rest\QueryParam(name="year", nullable=true, description="By default current year")
     * @Rest\QueryParam(name="month", nullable=true, description="By default current month")
     * @param ParamFetcher $paramFetcher
     */
    public function calendarAction(ParamFetcher $paramFetcher)
    {
        $today = new \DateTime();
        $month = $paramFetcher->get('month') !== null ? $paramFetcher->get('month') : $today->format('m');
        $year = $paramFetcher->get('year') !== null ? $paramFetcher->get('year') : $today->format('Y');

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

        return new Response(
            $this->serializer->serialize(
                $this->propertyCalendarRepository->getAllByYearAndMonth($year, $month),
                'json',
                SerializationContext::create()->setGroups(['property_calendar'])->setSerializeNull(true)
            ),
            200
        );
    }

    /**
     * @Rest\QueryParam(name="dateFrom")
     * @Rest\QueryParam(name="dateTo")
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function patchDaysAttributesAction($id, ParamFetcher $paramFetcher)
    {
        $dateFrom = \DateTime::createFromFormat('Y-m-d', $paramFetcher->get('dateFrom'));
        $dateTo = \DateTime::createFromFormat('Y-m-d', $paramFetcher->get('dateTo'));

        return [$dateFrom, $dateTo];
    }

    /**
     * @param $id
     * @param $dayId
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function patchDayAttributesAction($id, $dayId)
    {
    }
}
