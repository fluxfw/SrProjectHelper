<?php

namespace srag\Plugins\SrProjectHelper\Access;

use ilSrProjectHelperPlugin;
use srag\DIC\SrProjectHelper\DICTrait;
use srag\Plugins\SrProjectHelper\Config\Config;
use srag\Plugins\SrProjectHelper\Utils\SrProjectHelperTrait;

/**
 * Class Access
 *
 * @package srag\Plugins\SrProjectHelper\Access
 *
 * @author  studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 */
final class Access {

	use DICTrait;
	use SrProjectHelperTrait;
	const PLUGIN_CLASS_NAME = ilSrProjectHelperPlugin::class;
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
