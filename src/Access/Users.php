<?php

namespace srag\Plugins\SrGitlabHelper\Access;

use ilSrGitlabHelperPlugin;
use srag\DIC\SrGitlabHelper\DICTrait;
use srag\Plugins\SrGitlabHelper\Utils\SrGitlabHelperTrait;

/**
 * Class Users
 *
 * @package srag\Plugins\SrGitlabHelper\Access
 *
 * @author  studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 */
final class Users {

	use DICTrait;
	use SrGitlabHelperTrait;
	const PLUGIN_CLASS_NAME = ilSrGitlabHelperPlugin::class;
	/**
	 * @var self
	 */
	protected static $instance = null;


	/**
	 * @return self
	 */
	public static function getInstance(): self {
		if (self::$instance === null) {
			self::$instance = new self();
		}

		return self::$instance;
	}


	/**
	 * Users constructor
	 */
	private function __construct() {

	}


	/**
	 * @return int
	 */
	public function getUserId(): int {
		$user_id = self::dic()->user()->getId();

		// Fix login screen
		if ($user_id === 0 && boolval(self::dic()->settings()->get("pub_section"))) {
			$user_id = ANONYMOUS_USER_ID;
		}

		return intval($user_id);
	}
}
