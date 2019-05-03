<?php

namespace srag\Plugins\SrGitlabHelper\Utils;

use srag\Plugins\SrGitlabHelper\Access\Access;
use srag\Plugins\SrGitlabHelper\Access\Ilias;

/**
 * Trait SrGitlabHelperTrait
 *
 * @package srag\Plugins\SrGitlabHelper\Utils
 *
 * @author  studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 */
trait SrGitlabHelperTrait {

	/**
	 * @return Access
	 */
	protected static function access(): Access {
		return Access::getInstance();
	}


	/**
	 * @return Ilias
	 */
	protected static function ilias(): Ilias {
		return Ilias::getInstance();
	}
}
