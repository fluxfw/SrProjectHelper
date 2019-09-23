<?php

namespace srag\Plugins\SrProjectHelper\Utils;

use srag\Plugins\SrProjectHelper\Access\Access;
use srag\Plugins\SrProjectHelper\Access\Ilias;
use srag\Plugins\SrProjectHelper\Gitlab\Api;
use srag\Plugins\SrProjectHelper\Gitlab\Client;

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
     * @return Access
     */
    protected static function access() : Access
    {
        return Access::getInstance();
    }


    /**
     * @return Client
     */
    protected static function gitlab() : Client
    {
        return Api::getClient();
    }


    /**
     * @return Ilias
     */
    protected static function ilias() : Ilias
    {
        return Ilias::getInstance();
    }
}
