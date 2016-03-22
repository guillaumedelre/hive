<?php

namespace ApiBundle\Controller;

use CoreBundle\Entity\AbstractEntity;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Request\ParamFetcher;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Routing\ClassResourceInterface;
use FOS\RestBundle\Util\Codes;
use FOS\RestBundle\View\View;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;

class CalendarController extends FOSRestController implements ClassResourceInterface
{
    /**
     * Retourne une collection paginée
     *
     * @ApiDoc(
     *  resource=true,
     *  description="Récupère une collection paginée",
     *  section="Calendar",
     *  output="CoreBundle\Entity\CalendarEvent",
     *  statusCodes={
     *      200="Ok",
     *      400="Bad Request",
     *      500="Internal error"
     *  }
     * )
     * @Rest\QueryParam(name="from", requirements="\d+", nullable=true, description="Timestamp")
     * @Rest\QueryParam(name="to", requirements="\d+", nullable=true, description="Timestamp")
     * @Rest\QueryParam(name="limit", requirements="\d+", default="20", strict=true, nullable=true, description="Item count limit")
     * @Rest\QueryParam(name="page", requirements="\d+", default="0", strict=true, nullable=true, description="Current page of collection")
     */
    public function cgetAction(ParamFetcher $paramFetcher)
    {
        $limit   = $paramFetcher->get('limit', AbstractEntity::DEFAULT_LIMIT_API);
        $page    = $paramFetcher->get('page', 0);
        $from    = $paramFetcher->get('from', null);
        $to      = $paramFetcher->get('to', null);
        $me      = $this->get('core.service.me')->getUser();

        $collection = $this->get('core.repository.event')->getEventsBetween($me, $from, $to, $limit, $page * $limit);
        $events = $this->get('core.factory.calendarevent')->createFromEvents($collection);

        if(!is_array($events)) {
            throw $this->createNotFoundException();
        }

        $output = [
            "success" => 1,
            "result"  => $events,
        ];

        return View::create($output, Codes::HTTP_OK);
    }
}
