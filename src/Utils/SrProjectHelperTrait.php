<?php

namespace srag\Plugins\SrProjectHelper\Utils;

use srag\Plugins\SrProjectHelper\Repository;

/**
 * Trait SrProjectHelperTrait
 *
 * @package srag\Plugins\SrProjectHelper\Utils
 */
trait SrProjectHelperTrait
{

    /**
     * @return Repository
     */
    protected static function srProjectHelper() : Repository
    {
        return Repository::getInstance();
    }
}
