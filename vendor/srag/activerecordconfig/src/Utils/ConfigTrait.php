<?php

namespace srag\ActiveRecordConfig\SrProjectHelper\Utils;

use srag\ActiveRecordConfig\SrProjectHelper\Config\Repository as ConfigRepository;

/**
 * Trait ConfigTrait
 *
 * @package srag\ActiveRecordConfig\SrProjectHelper\Utils
 *
 * @author  studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 */
trait ConfigTrait
{

    /**
     * @return ConfigRepository
     */
    protected static function config() : ConfigRepository
    {
        return ConfigRepository::getInstance();
    }
}
