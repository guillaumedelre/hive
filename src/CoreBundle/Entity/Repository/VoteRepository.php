<?php
/**
 * Created by PhpStorm.
 * User: gdelre
 * Date: 13/03/16
 * Time: 11:48
 */

namespace CoreBundle\Entity\Repository;


class VoteRepository extends AbstractRepository
{
    const CHOICE_APPROVED  = true;
    const CHOICE_REFUSED   = false;

    const LABEL_APPROVED  = 'Approuvé';
    const LABEL_REFUSED   = 'Refusé';
}