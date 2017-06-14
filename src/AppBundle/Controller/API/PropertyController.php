<?php

namespace AppBundle\Controller\API;

use AppBundle\Entity\Attribute;
use AppBundle\Entity\Property;
use Doctrine\Bundle\DoctrineBundle\Registry;
use FOS\RestBundle\Controller\Annotations\View;
use FOS\RestBundle\Controller\FOSRestController;
use Symfony\Component\HttpFoundation\JsonResponse;

class PropertyController extends FOSRestController
{
    /**
     * @var Registry
     */
    private $registry;

    public function __construct(Registry $registry)
    {
        $this->registry = $registry;
    }
    /**
     * @View()
     */
    public function getPropertiesAction()
    {
        return $this->registry
            ->getManager()
            ->getRepository(Property::class)
            ->findAll();
    }
}
