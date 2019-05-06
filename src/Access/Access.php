<?php

namespace srag\Plugins\SrGitlabHelper\Access;

use ilSrGitlabHelperPlugin;
use srag\DIC\SrGitlabHelper\DICTrait;
use srag\Plugins\SrGitlabHelper\Config\Config;
use srag\Plugins\SrGitlabHelper\Utils\SrGitlabHelperTrait;

/**
 * Class Access
 *
 * @package srag\Plugins\SrGitlabHelper\Access
 *
 * @author  studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 */
final class Access {

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
	 * Access constructor
	 */
	private function __construct() {

	}


	/**
	 * @return bool
	 */
	public function currentUserHasRole(): bool {
		$user_id = self::ilias()->users()->getUserId();

		$user_roles = self::dic()->rbacreview()->assignedGlobalRoles($user_id);
		$config_roles = Config::getField(Config::KEY_ROLES);

		foreach ($user_roles as $user_role) {
			if (in_array($user_role, $config_roles)) {
				return true;
			}
		}

		return false;
	}
}
