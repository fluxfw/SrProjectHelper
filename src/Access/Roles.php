<?php

namespace srag\Plugins\SrProjectHelper\Access;

use ilSrProjectHelperPlugin;
use srag\DIC\SrProjectHelper\DICTrait;
use srag\Plugins\SrProjectHelper\Utils\SrProjectHelperTrait;

/**
 * Class Roles
 *
 * @package srag\Plugins\SrProjectHelper\Access
 *
 * @author  studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 */
final class Roles {

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
	 * Roles constructor
	 */
	private function __construct() {

	}


	/**
	 * @return array
	 */
	public function getAllRoles(): array {
		/**
		 * @var array $global_roles
		 * @var array $roles
		 */

		$global_roles = self::dic()->rbacreview()->getRolesForIDs(self::dic()->rbacreview()->getGlobalRoles(), false);

		$roles = [];
		foreach ($global_roles as $global_role) {
			$roles[$global_role["rol_id"]] = $global_role["title"];
		}

		return $roles;
	}
}
