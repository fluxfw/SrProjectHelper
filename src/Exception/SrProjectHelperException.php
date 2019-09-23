<?php

namespace srag\Plugins\SrProjectHelper\Exception;

use ilException;

/**
 * Class SrProjectHelperException
 *
 * @package srag\Plugins\SrProjectHelper\Exception
 *
 * @author  studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 */
class SrProjectHelperException extends ilException
{

    /**
     * SrProjectHelperException constructor
     *
     * @param string $message
     * @param int    $code
     */
    public function __construct(string $message, int $code = 0)
    {
        parent::__construct($message, $code);
    }
}
