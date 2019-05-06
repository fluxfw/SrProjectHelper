<?php

namespace srag\Plugins\SrGitlabHelper\Exception;

use ilException;

/**
 * Class SrGitlabHelperException
 *
 * @package srag\Plugins\SrGitlabHelper\Exception
 *
 * @author  studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 */
class SrGitlabHelperException extends ilException {

	/**
	 * SrGitlabHelperException constructor
	 *
	 * @param string $message
	 * @param int    $code
	 */
	public function __construct(string $message, int $code = 0) {
		parent::__construct($message, $code);
	}
}
