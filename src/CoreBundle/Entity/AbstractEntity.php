<?php
/**
 * Created by PhpStorm.
 * User: gdelre
 * Date: 08/03/16
 * Time: 19:41
 */

namespace CoreBundle\Entity;


abstract class AbstractEntity
{
    const DEFAULT_LIMIT_APP   = 5;
    const DEFAULT_LIMIT_ADMIN = 20;
    const DEFAULT_LIMIT_API   = 50;

    abstract public function __toString();
}