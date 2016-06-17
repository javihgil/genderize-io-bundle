<?php

namespace Jhg\GenderizeIoBundle\Genderizer\CacheHandler;

use Jhg\GenderizeIoClient\Model\Name;


/**
 * Interface CacheHandlerInterface
 *
 * @package Jhg\GenderizeIoBundle\Genderizer\CacheHandler
 * @author Ruben Harms <info@rubenharms.nl>
 */
interface CacheHandlerInterface {

    public function isCached($query, $expiryTime);
    public function cacheResult($result);
}