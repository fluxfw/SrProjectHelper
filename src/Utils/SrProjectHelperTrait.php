<?php

namespace srag\Plugins\SrProjectHelper\Utils;

use srag\Plugins\SrProjectHelper\Repository;

/**
 * Trait SrProjectHelperTrait
 *
 * @package srag\Plugins\SrProjectHelper\Utils
 *
 * @author  studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
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
